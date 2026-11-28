<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GrammarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\VocabularyController;
use App\Models\User;

// Hiển thị trang chủ cho guest; HomeController lấy chữ Hán ngẫu nhiên từ database và chuyển user sang dashboard.
Route::get('/', [HomeController::class, 'index'])->name('home');

// Hiển thị form đăng nhập; nếu đã đăng nhập thì chuyển thẳng đến dashboard.
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('pages/pagelog', ['page' => 'login']);
})->name('login');

// Xác thực thông tin đăng nhập, tạo session mới và chuyển user đến dashboard.
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])->onlyInput('email');
});

// Hiển thị form đăng ký; nếu đã đăng nhập thì chuyển thẳng đến dashboard.
Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('pages/pagelog', ['page' => 'register']);
});

// Tạo tài khoản mới, đăng nhập user vừa tạo và chuyển đến dashboard.
Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'full_name' => ['required', 'string', 'max:100'],
        'username' => ['required', 'string', 'max:50', 'unique:users,username'],
        'email' => ['required', 'email', 'max:120', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = User::create([
        'username' => $data['username'],
        'full_name' => $data['full_name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('dashboard');
});

// Hiển thị form yêu cầu đặt lại mật khẩu.
Route::get('/forgot-password', fn () => view('pages/pagelog', ['page' => 'forgot-password']));

// Gửi liên kết đặt lại mật khẩu đến email đã đăng ký.
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => ['required', 'email']]);
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'Chúng tôi đã gửi liên kết đặt lại mật khẩu vào email của bạn.')
        : back()->withErrors(['email' => 'Không tìm thấy tài khoản với email này.']);
});

// Hiển thị form đặt lại mật khẩu kèm token trong đường dẫn.
Route::get('/reset-password/{token}', fn (Request $request) =>
    view('pages/pagelog', ['page' => 'reset-password', 'request' => $request])
)->name('password.reset');

// Kiểm tra token và lưu mật khẩu mới đã được mã hóa.
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect('/login')->with('status', 'Mật khẩu đã được cập nhật.')
        : back()->withErrors(['email' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
});

// Đăng xuất, hủy session và chuyển đến trang thông báo.
Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Hiển thị dashboard; middleware auth chỉ cho phép user đã đăng nhập truy cập.
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/tim-kiem', [SearchController::class, 'index'])->name('search.index');
    Route::get('/ngu-phap', [GrammarController::class, 'index'])->name('grammar.user');
    Route::post('/ngu-phap/{grammarPoint}/progress', [GrammarController::class, 'updateProgress'])
        ->name('grammar.progress.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Tra cứu từ vựng cho guest; controller chuyển user đã đăng nhập sang trang riêng.
Route::get('/tra-cuu', [VocabularyController::class, 'index'])
    ->name('vocabulary.guest');

// Tìm từ vựng trong database bằng AJAX; chỉ user đã đăng nhập được sử dụng.
Route::get('/tu-vung/tim-kiem', [VocabularyController::class, 'searchUser'])
    ->middleware('auth')
    ->name('vocabulary.user.search');

// Hiển thị trang từ vựng dành riêng cho user đã đăng nhập.
Route::get('/tu-vung', [VocabularyController::class, 'userIndex'])
    ->middleware('auth')
    ->name('vocabulary.user');