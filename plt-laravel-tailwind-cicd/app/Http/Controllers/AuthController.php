<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($cred + ['is_active' => true], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng, hoặc tài khoản đang bị khóa.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended(Auth::user()->isAdmin() ? route('admin.dashboard') : route('trang-chu'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Role luôn là Student khi tự đăng ký, không nhận role từ form.
        $user = User::create($data + ['role' => 'Student']);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('trang-chu');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('gioi-thieu');
    }
}
