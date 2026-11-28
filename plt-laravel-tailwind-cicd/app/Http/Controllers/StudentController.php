<?php

namespace App\Http\Controllers;

use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\GrammarPoint;
use App\Models\Vocabulary;
use App\Models\WritingChar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function landing()
    {
        return view('landing');
    }

    public function home()
    {
        $today = Carbon::now();
        return view('student.home', [
            'highlights' => Vocabulary::inRandomOrder()->limit(3)->get(),
            'monthStart' => $today->copy()->startOfMonth(),
            'today' => $today,
        ]);
    }

    public function writing()
    {
        return view('student.writing', ['chars' => WritingChar::orderBy('id')->get()]);
    }

    /** Tra từ: tìm theo chữ Hán, pinyin (không cần dấu) hoặc nghĩa tiếng Việt. */
    public function vocab(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $word = null;
        if ($q !== '') {
            $word = Vocabulary::where('zh', $q)
                ->orWhere('vi', 'like', "%{$q}%")
                ->orWhere('py', 'like', "%{$q}%")
                ->first();
            if (! $word) {
                $needle = $this->plain($q);
                $word = Vocabulary::all()->first(fn ($v) => $this->plain($v->py) === $needle);
            }
        } else {
            $word = Vocabulary::where('zh', '你好')->first() ?? Vocabulary::first();
        }

        return view('student.vocab', [
            'q' => $q,
            'word' => $word,
            'suggestions' => Vocabulary::inRandomOrder()->limit(3)->get(),
        ]);
    }

    public function grammar(Request $request)
    {
        $hsk = (int) $request->query('hsk', 1);
        $q = trim((string) $request->query('q', ''));
        $items = GrammarPoint::where('hsk', $hsk)
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")->orWhere('py', 'like', "%{$q}%")->orWhere('zh', 'like', "%{$q}%");
            }))
            ->orderBy('id')->get();

        return view('student.grammar', ['items' => $items, 'hsk' => $hsk, 'q' => $q]);
    }

    public function review()
    {
        $sets = [];
        foreach ([1, 2, 3] as $level) {
            $sets[] = ['id' => (string) $level, 'name' => "Bộ đề ôn tập HSK {$level}", 'count' => Vocabulary::where('hsk', $level)->count()];
        }
        $sets[] = ['id' => 'all', 'name' => 'Bộ đề ôn tập tổng hợp', 'count' => Vocabulary::count()];

        return view('student.review', ['sets' => $sets, 'total' => Vocabulary::count()]);
    }

    public function reviewSet(string $set)
    {
        $query = Vocabulary::query();
        if ($set !== 'all') {
            abort_unless(in_array($set, ['1', '2', '3'], true), 404);
            $query->where('hsk', (int) $set);
        }
        return view('student.review-set', [
            'set' => $set,
            'items' => $query->orderBy('id')->get(['id', 'zh', 'py', 'vi'])->values(),
        ]);
    }

    public function exams()
    {
        $levels = [];
        for ($h = 1; $h <= 6; $h++) {
            $levels[$h] = ExamQuestion::where('hsk', $h)->count();
        }
        return view('student.exams', ['levels' => $levels, 'results' => ExamResult::where('exam', 'like', 'Thi thử HSK%')->count()]);
    }

    public function examTake(int $hsk)
    {
        $questions = ExamQuestion::where('hsk', $hsk)->orderBy('id')->get();
        abort_if($questions->isEmpty(), 404, 'Đề này đang được biên soạn.');
        return view('student.exam-take', ['hsk' => $hsk, 'questions' => $questions]);
    }

    public function examSubmit(Request $request, int $hsk)
    {
        $questions = ExamQuestion::where('hsk', $hsk)->orderBy('id')->get();
        abort_if($questions->isEmpty(), 404);

        $answers = (array) $request->input('answers', []);
        $correct = 0;
        $review = [];
        foreach ($questions as $q) {
            $picked = isset($answers[$q->id]) ? (int) $answers[$q->id] : null;
            $ok = $picked === $q->answer;
            $correct += $ok ? 1 : 0;
            $review[] = ['q' => $q, 'picked' => $picked, 'ok' => $ok];
        }
        $score = (int) round($correct / $questions->count() * 100);

        ExamResult::create([
            'user_id' => $request->user()->id,
            'user_name' => $request->user()->name,
            'exam' => "Thi thử HSK{$hsk}",
            'score' => $score,
            'taken_at' => now()->toDateString(),
        ]);

        return view('student.exam-result', compact('hsk', 'score', 'correct', 'review') + ['total' => $questions->count()]);
    }

    /** Bỏ dấu thanh và khoảng trắng để so khớp pinyin không dấu. */
    private function plain(string $s): string
    {
        return strtolower(preg_replace('/\s+/', '', Str::ascii($s)));
    }
}
