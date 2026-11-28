<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VocabularyController extends Controller
{
    /**
     * Mở từ được chọn từ modal; nếu không có word_id thì hiển thị gợi ý ngẫu nhiên.
     */
    public function userIndex(Request $request): View
    {
        // Kiểm tra ID và bảo đảm từ được yêu cầu còn tồn tại.
        $filters = $request->validate([
            'word_id' => ['nullable', 'integer', 'exists:vocabularies,id'],
        ]);
        $selectedWord = isset($filters['word_id'])
            ? Vocabulary::query()->findOrFail($filters['word_id'])
            : null;

        $goiY = Vocabulary::query()
            ->inRandomOrder()
            ->limit(8)
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

        if ($selectedWord) {
                // Đưa từ được chọn lên đầu để nội dung trang và JS cùng hiển thị từ đó.
            $goiY = $goiY
                ->reject(fn (Vocabulary $word) => $word->getKey() === $selectedWord->getKey())
                ->prepend($selectedWord)
                ->values();
        }

        $tuBanDau = $selectedWord ?? $goiY->first();

        return view('pages.vocabulary-user', [
            'goiY' => $goiY,
            'tuBanDau' => $tuBanDau,
        ]);
    }

    /**
     * API gợi ý tối đa 12 từ theo chữ Hán, pinyin, nghĩa và câu ví dụ.
     */
    public function searchUser(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'q' => ['required', 'string', 'max:80'],
        ]);

        $results = Vocabulary::query()
            ->search(trim($filters['q']))
            ->orderBy('hanzi')
            ->limit(12)
            ->get([
                'hanzi',
                'pinyin',
                'meaning',
                'part_of_speech',
                'hsk_level',
                'example_hanzi',
                'example_pinyin',
                'example_meaning',
            ]);

        return response()->json($results);
    }

    /** Trang tra cứu công khai; người đã đăng nhập được chuyển sang trang riêng. */
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('vocabulary.user');
        }

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
        ]);
        $query = trim($filters['q'] ?? '');

        $results = Vocabulary::query()
            ->search($query)
            ->orderBy('hanzi')
            ->paginate(12)
            ->withQueryString();

        return view('pages.vocabulary-guest', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
