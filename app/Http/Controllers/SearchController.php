<?php

namespace App\Http\Controllers;

use App\Models\GrammarExample;
use App\Models\GrammarPoint;
use App\Models\Vocabulary;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/** Trả kết quả tìm kiếm cho modal navbar. */
class SearchController extends Controller
{
    /**
     * Tìm từ vựng, ngữ pháp và câu theo từ khóa hoặc đoạn tiếng Trung/Việt.
     * Các khóa JSON phải khớp với renderResults() trong app.js.
     */
    public function index(Request $request): JsonResponse
    {
        // Kiểm tra độ dài, bỏ khoảng trắng thừa và tạo mẫu tìm gần đúng.
        $filters = $request->validate([
            'q' => ['required', 'string', 'max:80'],
        ]);
        $query = trim($filters['q'] ?? '');
        $pattern = '%'.$query.'%';

        // Tìm các từ đã biết nằm trong đoạn để gộp với kết quả khớp trực tiếp.
        $vocabularyIdsFromText = $this->findVocabularyIdsFromText($query);
        $grammarPointIdsFromText = $this->findGrammarPointIdsFromText($query);

        $vocabularies = Vocabulary::query()
            ->where(function (Builder $matches) use ($pattern) {
                $matches->where('hanzi', 'like', $pattern)
                    ->orWhere('pinyin', 'like', $pattern)
                    ->orWhere('meaning', 'like', $pattern)
                    ->orWhere('part_of_speech', 'like', $pattern);
            })
            // Gộp hai kiểu khớp trong một truy vấn, không tạo dòng trùng.
            ->when($vocabularyIdsFromText !== [], function (Builder $matches) use ($vocabularyIdsFromText) {
                $matches->orWhereIn('id', $vocabularyIdsFromText);
            })
            ->orderBy('hanzi')
            ->get([
                'id',
                'hanzi',
                'pinyin',
                'meaning',
                'part_of_speech',
                'hsk_level',
                'example_hanzi',
                'example_pinyin',
                'example_meaning',
            ]);

        // Tìm theo tên, cấu trúc, giải thích, ghi chú; tải ví dụ trước để tránh N+1 query.
        $grammarPoints = GrammarPoint::query()
            ->with(['examples' => fn (HasMany $examples) => $examples->orderBy('order_index')])
            ->where(function (Builder $matches) use ($pattern) {
                $matches->where('title', 'like', $pattern)
                    ->orWhere('structure', 'like', $pattern)
                    ->orWhere('explanation', 'like', $pattern)
                    ->orWhere('note', 'like', $pattern);
            })
            ->when($grammarPointIdsFromText !== [], function (Builder $matches) use ($grammarPointIdsFromText) {
                $matches->orWhereIn('id', $grammarPointIdsFromText);
            })
            ->orderBy('hsk_level')
            ->orderBy('order_index')
            ->get();

        // Tìm câu riêng để vẫn hiện câu khớp dù tên từ/ngữ pháp không khớp.
        $vocabularySentences = Vocabulary::query()
            ->where(function (Builder $matches) use ($pattern) {
                $matches->where('example_hanzi', 'like', $pattern)
                    ->orWhere('example_pinyin', 'like', $pattern)
                    ->orWhere('example_meaning', 'like', $pattern);
            })
            ->orderBy('hanzi')
            ->get(['id', 'hanzi', 'example_hanzi', 'example_pinyin', 'example_meaning']);

        $grammarSentences = GrammarExample::query()
            ->with('grammarPoint:id,title')
            ->where(function (Builder $matches) use ($pattern) {
                $matches->where('hanzi', 'like', $pattern)
                    ->orWhere('pinyin', 'like', $pattern)
                    ->orWhere('meaning', 'like', $pattern);
            })
            ->orderBy('order_index')
            ->get(['id', 'grammar_point_id', 'hanzi', 'pinyin', 'meaning']);

        return response()->json([
            'vocabularies' => $vocabularies,
            'grammarPoints' => $grammarPoints,
            'vocabularySentences' => $vocabularySentences,
            'grammarSentences' => $grammarSentences,
        ]);
    }

    /**
     * Lấy từ có trong đoạn: tiếng Trung ghép cụm dài nhất, tiếng Việt dò từng từ trong nghĩa.
     *
     * @return array<int, int>
     */
    private function findVocabularyIdsFromText(string $text): array
    {
        if (preg_match('/\p{Han}/u', $text) === 1) {
            // Lấy danh sách chữ Hán và độ dài từ tối đa để phân tích đoạn.
            $words = Vocabulary::query()
                ->distinct()
                ->pluck('hanzi')
                ->filter()
                ->unique()
                ->values()
                ->all();
            $wordSet = array_fill_keys($words, true);
            $maximumWordLength = 0;

            foreach ($words as $word) {
                $characters = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
                $maximumWordLength = max($maximumWordLength, \count($characters ?: []));
            }

            $characters = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
            if ($maximumWordLength === 0 || ! $characters) {
                return [];
            }

            $matchedWords = [];
            for ($offset = 0, $textLength = \count($characters); $offset < $textLength;) {
                $matchedLength = 0;
                $searchLength = min($maximumWordLength, $textLength - $offset);

                // Ưu tiên cụm dài nhất; nếu không khớp thì bỏ qua một ký tự.
                for ($length = $searchLength; $length > 0; $length--) {
                    $candidate = implode('', \array_slice($characters, $offset, $length));
                    if (isset($wordSet[$candidate])) {
                        $matchedWords[] = $candidate;
                        $matchedLength = $length;
                        break;
                    }
                }

                $offset += $matchedLength ?: 1;
            }

            if ($matchedWords === []) {
                return [];
            }

            // Một chữ Hán có thể có nhiều pinyin; trả về mọi bản ghi tương ứng.
            return Vocabulary::query()
                ->whereIn('hanzi', array_unique($matchedWords))
                ->pluck('id')
                ->all();
        }

        $tokens = preg_split('/[^\p{L}\p{N}]+/u', Str::lower($text), -1, PREG_SPLIT_NO_EMPTY);
        if (! $tokens) {
            return [];
        }

        $pinyinIds = $this->findPinyinVocabularyIdsFromText($text);
        // Dò nghĩa tiếng Việt; đồng thời ghép pinyin không dấu theo từ có trong DB.
        $meaningIds = Vocabulary::query()
            ->where(function (Builder $matches) use ($tokens) {
                foreach (array_unique($tokens) as $token) {
                    $matches->orWhere('meaning', 'like', '%'.$token.'%');
                }
            })
            ->pluck('id')
            ->all();

        return array_values(array_unique(array_merge($pinyinIds, $meaningIds)));
    }

    /**
     * Ghép các âm tiết pinyin theo từ điển, hỗ trợ pinyin có hoặc không dấu thanh.
     *
     * @return array<int, int>
     */
    private function findPinyinVocabularyIdsFromText(string $text): array
    {
        $syllables = preg_split('/\s+/', $this->normalizePinyin($text), -1, PREG_SPLIT_NO_EMPTY);
        if (! $syllables) {
            return [];
        }

        $pinyinToIds = [];
        foreach (Vocabulary::query()->whereNotNull('pinyin')->get(['id', 'pinyin']) as $vocabulary) {
            $pinyin = str_replace(' ', '', $this->normalizePinyin((string) $vocabulary->getAttribute('pinyin')));
            if ($pinyin !== '') {
                $pinyinToIds[$pinyin][] = $vocabulary->getKey();
            }
        }

        $matchedIds = [];
        for ($offset = 0, $syllableCount = \count($syllables); $offset < $syllableCount;) {
            $candidate = '';
            $matchedLength = 0;
            $matchedIdsAtOffset = [];

            for ($index = $offset; $index < $syllableCount; $index++) {
                $candidate .= $syllables[$index];
                if (isset($pinyinToIds[$candidate])) {
                    $matchedLength = $index - $offset + 1;
                    $matchedIdsAtOffset = $pinyinToIds[$candidate];
                }
            }

            if ($matchedLength > 0) {
                $matchedIds = array_merge($matchedIds, $matchedIdsAtOffset);
                $offset += $matchedLength;
            } else {
                $offset++;
            }
        }

        return array_values(array_unique($matchedIds));
    }

    private function normalizePinyin(string $text): string
    {
        $ascii = Str::lower(Str::ascii($text));
        $normalized = preg_replace('/[^a-z0-9]+/', ' ', $ascii);

        return trim(preg_replace('/\s+/', ' ', $normalized ?? ''));
    }

    /**
     * Nhận diện điểm ngữ pháp bằng dấu hiệu chữ Hán và câu ví dụ trong DB.
     *
     * @return array<int, int>
     */
    private function findGrammarPointIdsFromText(string $text): array
    {
        $containsHanzi = preg_match('/\p{Han}/u', $text) === 1;
        $normalizedText = Str::lower($text);
        $normalizedPinyin = $this->normalizePinyin($text);
        $grammarPoints = GrammarPoint::query()
            ->with(['examples' => fn (HasMany $examples) => $examples->select([
                'id',
                'grammar_point_id',
                'hanzi',
                'pinyin',
                'meaning',
            ])])
            ->get(['id', 'title', 'structure']);

        $matchedIds = [];

        foreach ($grammarPoints as $grammarPoint) {
            $markers = [];
            foreach ([$grammarPoint->title, $grammarPoint->structure] as $field) {
                preg_match_all('/\p{Han}+/u', (string) $field, $matches);
                $markers = array_merge($markers, $matches[0]);
            }

            $hasMarker = $containsHanzi && collect(array_unique($markers))
                ->contains(fn (string $marker) => Str::contains($text, $marker));

            $hasExample = $grammarPoint->examples->contains(function (GrammarExample $example) use (
                $containsHanzi,
                $normalizedPinyin,
                $normalizedText,
                $text,
            ) {
                $hanziMatches = $containsHanzi
                    && $example->hanzi !== ''
                    && Str::contains($text, $example->hanzi);
                $pinyinMatches = ! $containsHanzi
                    && $example->pinyin
                    && Str::contains($normalizedPinyin, $this->normalizePinyin($example->pinyin));
                $meaningMatches = ! $containsHanzi
                    && $example->meaning !== ''
                    && Str::contains($normalizedText, Str::lower($example->meaning));

                return $hanziMatches || $pinyinMatches || $meaningMatches;
            });

            if ($hasMarker || $hasExample) {
                $matchedIds[] = $grammarPoint->getKey();
            }
        }

        return $matchedIds;
    }
}
