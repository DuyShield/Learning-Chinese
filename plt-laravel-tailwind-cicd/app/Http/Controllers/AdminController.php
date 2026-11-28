<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ExamResult;
use App\Models\GrammarPoint;
use App\Models\User;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private const HSK = ['required', 'integer', 'between:1,9'];

    /** Cấu hình các trang danh sách. Thêm một mục ở đây là có thêm một trang quản lý. */
    private function kinds(): array
    {
        return [
            'users' => [
                'model' => User::class, 'title' => 'Danh sách users', 'sub' => 'Quản lý toàn bộ dữ liệu hệ thống',
                'placeholder' => 'Tìm kiếm...', 'search' => ['name', 'email'], 'creatable' => true, 'filter' => null,
                'fields' => [
                    ['name' => 'name', 'label' => 'Họ tên', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                    ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'rules' => ['required', 'email', 'max:255']],
                    ['name' => 'role', 'label' => 'Vai trò', 'type' => 'select', 'options' => ['Student', 'Teacher', 'Parent', 'Admin'], 'rules' => ['required', 'in:Student,Teacher,Parent,Admin']],
                    ['name' => 'phone', 'label' => 'Điện thoại', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:20']],
                    ['name' => 'password', 'label' => 'Mật khẩu (để trống thì dùng "password")', 'type' => 'password', 'rules' => ['nullable', 'string', 'min:6'], 'only' => 'create'],
                    ['name' => 'is_active', 'label' => 'Đang hoạt động', 'type' => 'checkbox', 'rules' => ['boolean']],
                ],
            ],
            'tu-vung' => [
                'model' => Vocabulary::class, 'title' => 'Danh sách từ vựng', 'sub' => 'Quản lý toàn bộ từ vựng trong hệ thống',
                'placeholder' => 'Tìm kiếm từ vựng...', 'search' => ['zh', 'py', 'vi'], 'creatable' => true, 'filter' => 'hsk',
                'fields' => [
                    ['name' => 'zh', 'label' => 'Hán từ', 'type' => 'text', 'rules' => ['required', 'string', 'max:50']],
                    ['name' => 'py', 'label' => 'Pinyin', 'type' => 'text', 'rules' => ['required', 'string', 'max:100']],
                    ['name' => 'vi', 'label' => 'Nghĩa', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                    ['name' => 'type', 'label' => 'Loại từ', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:30']],
                    ['name' => 'hsk', 'label' => 'Cấp độ HSK', 'type' => 'number', 'rules' => self::HSK],
                    ['name' => 'topic', 'label' => 'Chủ đề', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:60']],
                    ['name' => 'example', 'label' => 'Ví dụ (tiếng Trung)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                    ['name' => 'example_vi', 'label' => 'Ví dụ (tiếng Việt)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                ],
            ],
            'giao-trinh' => [
                'model' => Course::class, 'title' => 'Danh sách giáo trình', 'sub' => 'Quản lý toàn bộ giáo trình trong hệ thống',
                'placeholder' => 'Tìm kiếm giáo trình...', 'search' => ['name', 'code', 'author'], 'creatable' => true, 'filter' => 'hsk',
                'fields' => [
                    ['name' => 'code', 'label' => 'Mã giáo trình', 'type' => 'text', 'rules' => ['required', 'string', 'max:30']],
                    ['name' => 'name', 'label' => 'Tên giáo trình', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                    ['name' => 'description', 'label' => 'Mô tả', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                    ['name' => 'hsk', 'label' => 'Cấp độ HSK', 'type' => 'number', 'rules' => self::HSK],
                    ['name' => 'lessons', 'label' => 'Số bài học', 'type' => 'number', 'rules' => ['required', 'integer', 'min:0']],
                    ['name' => 'words', 'label' => 'Số từ vựng', 'type' => 'number', 'rules' => ['required', 'integer', 'min:0']],
                    ['name' => 'author', 'label' => 'Tác giả', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                    ['name' => 'is_active', 'label' => 'Đang hoạt động', 'type' => 'checkbox', 'rules' => ['boolean']],
                ],
            ],
            'ngu-phap' => [
                'model' => GrammarPoint::class, 'title' => 'Danh sách ngữ pháp', 'sub' => 'Quản lý toàn bộ ngữ pháp trong hệ thống',
                'placeholder' => 'Tìm kiếm ngữ pháp...', 'search' => ['zh', 'py', 'name'], 'creatable' => true, 'filter' => 'hsk',
                'fields' => [
                    ['name' => 'zh', 'label' => 'Hán tự', 'type' => 'text', 'rules' => ['required', 'string', 'max:50']],
                    ['name' => 'py', 'label' => 'Pinyin', 'type' => 'text', 'rules' => ['required', 'string', 'max:100']],
                    ['name' => 'name', 'label' => 'Tên điểm ngữ pháp', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                    ['name' => 'hsk', 'label' => 'Cấp độ HSK', 'type' => 'number', 'rules' => self::HSK],
                    ['name' => 'description', 'label' => 'Giải thích', 'type' => 'textarea', 'rules' => ['required', 'string']],
                    ['name' => 'example', 'label' => 'Ví dụ', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                ],
            ],
            'lich-su' => [
                'model' => ExamResult::class, 'title' => 'Lịch sử kiểm tra', 'sub' => 'Theo dõi kết quả các bài thi thử',
                'placeholder' => 'Tìm theo người làm bài...', 'search' => ['user_name', 'exam'], 'creatable' => false, 'filter' => 'ket-qua',
                'fields' => [],
            ],
        ];
    }

    private function kind(string $kind): array
    {
        $kinds = $this->kinds();
        abort_unless(isset($kinds[$kind]), 404);
        return $kinds[$kind] + ['key' => $kind];
    }

    public function dashboard()
    {
        $roles = User::select('role', DB::raw('count(*) as total'))->groupBy('role')->pluck('total', 'role');
        $totalUsers = max(1, $roles->sum());

        // Người dùng đăng ký theo tháng của năm hiện tại, tính ở PHP để chạy được trên cả MySQL và SQLite.
        $months = array_fill(1, 12, 0);
        foreach (User::whereYear('created_at', now()->year)->get(['created_at']) as $u) {
            $months[(int) $u->created_at->format('n')]++;
        }

        return view('admin.dashboard', [
            'stats' => [
                ['icon' => '👥', 'label' => 'Tổng người dùng', 'value' => User::count(), 'color' => 'bg-mint'],
                ['icon' => '📚', 'label' => 'Từ vựng', 'value' => Vocabulary::count(), 'color' => 'bg-lemon'],
                ['icon' => '📖', 'label' => 'Giáo trình', 'value' => Course::count(), 'color' => 'bg-brand'],
                ['icon' => '✏️', 'label' => 'Ngữ pháp', 'value' => GrammarPoint::count(), 'color' => 'bg-salmon'],
            ],
            'months' => $months,
            'roles' => collect(['Student' => 'Học sinh', 'Teacher' => 'Giáo viên', 'Parent' => 'Phụ huynh', 'Admin' => 'Admin'])
                ->map(fn ($label, $role) => ['label' => $label, 'pct' => (int) round(($roles[$role] ?? 0) / $totalUsers * 100)])
                ->values(),
            'recent' => User::latest('id')->limit(5)->get(),
            'totalUsers' => User::count(),
        ]);
    }

    public function index(Request $request, string $kind)
    {
        $cfg = $this->kind($kind);
        $model = $cfg['model'];
        $q = trim((string) $request->query('q', ''));
        $filter = $request->query('f');

        $base = $model::query();
        $query = clone $base;
        if ($q !== '') {
            $query->where(function ($w) use ($cfg, $q) {
                foreach ($cfg['search'] as $col) {
                    $w->orWhere($col, 'like', "%{$q}%");
                }
            });
        }
        if ($cfg['filter'] === 'hsk' && $filter) {
            $query->where('hsk', (int) $filter);
        }
        if ($cfg['filter'] === 'ket-qua' && in_array($filter, ['dat', 'khong-dat'], true)) {
            $query->where('score', $filter === 'dat' ? '>=' : '<', 60);
        }

        return view('admin.list', [
            'cfg' => $cfg,
            'rows' => $query->orderBy('id')->paginate(15)->withQueryString(),
            'q' => $q,
            'filter' => $filter,
            'stats' => $this->stats($kind, $base, $query),
            'showTabs' => $request->routeIs('admin.quan-ly'),
        ]);
    }

    public function create(string $kind)
    {
        $cfg = $this->kind($kind);
        abort_unless($cfg['creatable'], 404);
        return view('admin.form', ['cfg' => $cfg, 'row' => null]);
    }

    public function store(Request $request, string $kind)
    {
        $cfg = $this->kind($kind);
        abort_unless($cfg['creatable'], 404);
        $data = $this->validated($request, $cfg, null);
        if ($kind === 'users' && empty($data['password'])) {
            $data['password'] = 'password';
        }
        $cfg['model']::create($data);
        return redirect()->route('admin.list', $kind)->with('ok', 'Đã thêm mới.');
    }

    public function edit(string $kind, int $id)
    {
        $cfg = $this->kind($kind);
        abort_unless($cfg['creatable'], 404);
        return view('admin.form', ['cfg' => $cfg, 'row' => $cfg['model']::findOrFail($id)]);
    }

    public function update(Request $request, string $kind, int $id)
    {
        $cfg = $this->kind($kind);
        abort_unless($cfg['creatable'], 404);
        $row = $cfg['model']::findOrFail($id);
        $data = $this->validated($request, $cfg, $row);
        if ($kind === 'users' && empty($data['password'])) {
            unset($data['password']);
        }
        $row->update($data);
        return redirect()->route('admin.list', $kind)->with('ok', 'Đã cập nhật.');
    }

    public function destroy(Request $request, string $kind, int $id)
    {
        $cfg = $this->kind($kind);
        $row = $cfg['model']::findOrFail($id);
        if ($kind === 'users' && $row->id === $request->user()->id) {
            return back()->withErrors(['xoa' => 'Không thể tự xóa tài khoản đang đăng nhập.']);
        }
        $row->delete();
        return redirect()->route('admin.list', $kind)->with('ok', 'Đã xóa.');
    }

    private function validated(Request $request, array $cfg, $row): array
    {
        $isCreate = $row === null;
        $rules = [];
        foreach ($cfg['fields'] as $f) {
            if (($f['only'] ?? null) === 'create' && ! $isCreate) {
                continue;
            }
            $r = $f['rules'];
            if ($cfg['key'] === 'users' && $f['name'] === 'email') {
                $r[] = 'unique:users,email' . ($row ? ",{$row->id}" : '');
            }
            if ($cfg['key'] === 'giao-trinh' && $f['name'] === 'code') {
                $r[] = 'unique:courses,code' . ($row ? ",{$row->id}" : '');
            }
            $rules[$f['name']] = $r;
        }
        $data = $request->validate($rules);
        foreach ($cfg['fields'] as $f) {
            if ($f['type'] === 'checkbox') {
                $data[$f['name']] = $request->boolean($f['name']);
            }
        }
        return $data;
    }

    private function stats(string $kind, $base, $filtered): array
    {
        $found = (clone $filtered)->count();
        return match ($kind) {
            'users' => [
                ['Tổng số', (clone $base)->count(), '📊', 'bg-brand/15'],
                ['Hoạt động', (clone $base)->where('is_active', true)->count(), '✅', 'bg-mint/40'],
                ['Không hoạt động', (clone $base)->where('is_active', false)->count(), '❌', 'bg-salmon/30'],
                ['Kết quả tìm kiếm', $found, '🔍', 'bg-lemon/40'],
            ],
            'giao-trinh' => [
                ['Tổng giáo trình', (clone $base)->count(), '📚', 'bg-brand/15'],
                ['Đang hoạt động', (clone $base)->where('is_active', true)->count(), '✅', 'bg-mint/40'],
                ['Tạm dừng', (clone $base)->where('is_active', false)->count(), '⛔', 'bg-salmon/30'],
                ['Kết quả tìm kiếm', $found, '🔍', 'bg-lemon/40'],
            ],
            'lich-su' => [
                ['Tổng bài thi', (clone $base)->count(), '📝', 'bg-brand/15'],
                ['Đạt', (clone $base)->where('score', '>=', 60)->count(), '✅', 'bg-mint/40'],
                ['Không đạt', (clone $base)->where('score', '<', 60)->count(), '❌', 'bg-salmon/30'],
                ['Điểm trung bình', round((clone $base)->avg('score') ?? 0) . ' đ', '📈', 'bg-lemon/40'],
            ],
            default => [
                ['Tổng số', (clone $base)->count(), '📊', 'bg-brand/15'],
                ['Kết quả tìm kiếm', $found, '🔍', 'bg-lemon/40'],
            ],
        };
    }
}
