<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;

/** Luyện nói, Luyện nghe, Luyện ngữ pháp và luyện từ vựng dùng chung một bộ máy luyện tập. */
class PracticeController extends Controller
{
    /** Cấu hình từng công cụ. 'type' quyết định giao diện luyện tập: speak (ghi âm), listen (gõ đáp án). */
    private function tools(): array
    {
        return [
            'noi' => [
                'route' => 'luyen-noi', 'title' => 'Luyện nói', 'sub' => 'Thực hành nói tiếng Trung', 'icon' => 'mic',
                'heading' => 'Công cụ luyện nói', 'modeLabel' => 'Chế độ luyện nói', 'type' => 'speak',
                'modes' => ['tu' => 'Đọc từ vựng', 'cau' => 'Đọc theo câu', 'hoi-thoai' => 'Hội thoại theo chủ đề'],
                'topic' => true, 'count' => false, 'start' => 'Bắt đầu luyện nói',
            ],
            'nghe' => [
                'route' => 'luyen-nghe', 'title' => 'Luyện nghe', 'sub' => 'Thực hành nghe tiếng Trung', 'icon' => 'headphones',
                'heading' => 'Công cụ luyện nghe', 'modeLabel' => 'Chế độ luyện nghe', 'type' => 'listen',
                'modes' => ['tu' => 'Nghe rõ từ', 'dien' => 'Nghe điền từ vào câu', 'chep' => 'Nghe chép cả câu'],
                'topic' => true, 'count' => false, 'start' => 'Bắt đầu luyện nghe',
            ],
            'ngu-phap' => [
                'route' => 'luyen-ngu-phap', 'title' => 'Luyện ngữ pháp', 'sub' => 'Thực hành nói tiếng Trung', 'icon' => 'cap',
                'heading' => 'Công cụ luyện ngữ pháp', 'modeLabel' => 'Chế độ luyện nói', 'type' => 'speak',
                'modes' => ['don' => 'Chữ đơn', 'ghep' => 'Từ ghép', 'cau' => 'Câu'],
                'topic' => false, 'count' => true, 'start' => 'Bắt đầu luyện ngữ pháp',
            ],
        ];
    }

    private function tool(string $kind): array
    {
        $tools = $this->tools();
        abort_unless(isset($tools[$kind]), 404);
        return $tools[$kind] + ['kind' => $kind];
    }

    public function select(string $kind)
    {
        return view('student.tool', ['tool' => $this->tool($kind), 'topics' => $this->topics()]);
    }

    public function start(Request $request, string $kind)
    {
        $tool = $this->tool($kind);
        $mode = $request->query('mode');
        if (! isset($tool['modes'][$mode])) {
            return redirect()->route($tool['route']);
        }
        $hsk = max(1, min(6, (int) $request->query('hsk', 1)));
        $topic = (string) $request->query('topic', '');
        $count = max(1, min(40, (int) $request->query('count', 10)));

        $pool = Vocabulary::where('hsk', $hsk)->get();
        if ($pool->isEmpty()) {
            $pool = Vocabulary::all(); // HSK chưa có từ mẫu thì lấy toàn bộ
        }
        if ($tool['topic'] && $topic !== '' && $pool->where('topic', $topic)->isNotEmpty()) {
            $pool = $pool->where('topic', $topic);
        }
        if ($kind === 'ngu-phap') {
            $pool = match ($mode) {
                'don' => $pool->filter(fn ($v) => mb_strlen($v->zh) === 1),
                'ghep' => $pool->filter(fn ($v) => mb_strlen($v->zh) >= 2),
                default => $pool,
            };
            if ($pool->isEmpty()) {
                $pool = Vocabulary::all();
            }
        }

        $items = $pool->shuffle()->take($count)->values()->map(fn ($v) => $this->item($kind, $mode, $v))->all();

        return view('student.practice', [
            'tool' => $tool,
            'config' => ['type' => $tool['type'], 'items' => $items, 'back' => route($tool['route'])],
        ]);
    }

    public function vocabPractice(Request $request)
    {
        $hsk = (int) $request->query('hsk', 0);
        $pool = Vocabulary::when($hsk > 0, fn ($q) => $q->where('hsk', $hsk))->get();
        $items = $pool->shuffle()->values()->map(fn ($v) => [
            'zh' => $v->zh, 'py' => $v->py, 'vi' => $v->vi, 'type' => $v->type,
        ])->all();

        return view('student.practice', [
            'tool' => ['title' => 'Luyện từ vựng', 'sub' => 'Nắm vững từ vựng tiếng Trung', 'icon' => 'stack'],
            'config' => ['type' => 'vocab', 'items' => $items, 'back' => route('tu-vung')],
        ]);
    }

    /** Biến một từ vựng thành một câu hỏi luyện tập. */
    private function item(string $kind, string $mode, Vocabulary $v): array
    {
        $sentence = $v->example ?: $v->zh;
        $sentenceVi = $v->example_vi ?: $v->vi;

        if ($kind === 'nghe') {
            return match ($mode) {
                'tu' => ['speak' => $v->zh, 'answers' => [$v->zh, $v->py], 'hint' => "{$v->zh} · {$v->py} · {$v->vi}"],
                'dien' => ['speak' => $sentence, 'display' => str_replace($v->zh, '＿＿', $sentence), 'answers' => [$v->zh, $v->py], 'hint' => "{$v->zh} ({$v->py})"],
                default => ['speak' => $sentence, 'answers' => [$sentence], 'hint' => $sentence],
            };
        }

        $asSentence = ($kind === 'noi' && $mode !== 'tu') || ($kind === 'ngu-phap' && $mode === 'cau');
        return $asSentence
            ? ['zh' => $sentence, 'py' => '', 'vi' => $sentenceVi]
            : ['zh' => $v->zh, 'py' => $v->py, 'vi' => $v->vi];
    }

    private function topics(): array
    {
        return Vocabulary::query()->whereNotNull('topic')->distinct()->orderBy('topic')->pluck('topic')->all();
    }
}
