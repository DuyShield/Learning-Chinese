<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\StudentController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('gioi-thieu'));
Route::get('/gioi-thieu', [StudentController::class, 'landing'])->name('gioi-thieu');

// Đăng nhập, đăng ký, đăng xuất
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login']);
    Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register']);
});
Route::post('/dang-xuat', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Phía học viên
Route::middleware('auth')->group(function () {
    Route::get('/trang-chu', [StudentController::class, 'home'])->name('trang-chu');
    Route::get('/luyen-viet', [StudentController::class, 'writing'])->name('luyen-viet');

    Route::get('/tu-vung', [StudentController::class, 'vocab'])->name('tu-vung');
    Route::get('/tu-vung/luyen', [PracticeController::class, 'vocabPractice'])->name('tu-vung.luyen');

    Route::get('/ngu-phap', [StudentController::class, 'grammar'])->name('ngu-phap');

    Route::get('/on-tap', [StudentController::class, 'review'])->name('on-tap');
    Route::get('/on-tap/{set}', [StudentController::class, 'reviewSet'])->name('on-tap.bo');

    Route::get('/luyen-thi', [StudentController::class, 'exams'])->name('luyen-thi');
    Route::get('/luyen-thi/{hsk}', [StudentController::class, 'examTake'])->whereNumber('hsk')->name('luyen-thi.lam');
    Route::post('/luyen-thi/{hsk}', [StudentController::class, 'examSubmit'])->whereNumber('hsk')->name('luyen-thi.nop');

    // Luyện nói, luyện nghe, luyện ngữ pháp: cùng một bộ máy luyện tập
    foreach (['noi' => 'luyen-noi', 'nghe' => 'luyen-nghe', 'ngu-phap' => 'luyen-ngu-phap'] as $kind => $path) {
        Route::get("/{$path}", [PracticeController::class, 'select'])->defaults('kind', $kind)->name($path);
        Route::get("/{$path}/bai", [PracticeController::class, 'start'])->defaults('kind', $kind)->name("{$path}.bai");
    }
});

// Phía quản trị
Route::prefix('admin')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/quan-ly', [AdminController::class, 'index'])->defaults('kind', 'users')->name('admin.quan-ly');
    Route::get('/{kind}', [AdminController::class, 'index'])->name('admin.list');
    Route::get('/{kind}/them', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/{kind}', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/{kind}/{id}/sua', [AdminController::class, 'edit'])->whereNumber('id')->name('admin.edit');
    Route::put('/{kind}/{id}', [AdminController::class, 'update'])->whereNumber('id')->name('admin.update');
    Route::delete('/{kind}/{id}', [AdminController::class, 'destroy'])->whereNumber('id')->name('admin.destroy');
});
