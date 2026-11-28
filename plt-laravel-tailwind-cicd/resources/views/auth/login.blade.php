@extends('auth.shell')
@section('title', 'Đăng nhập')
@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-4">@csrf
    <label class="block text-sm font-medium">Email
        <input name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-xl border px-4 py-2">
    </label>
    <label class="block text-sm font-medium">Mật khẩu
        <input name="password" type="password" required class="mt-1 w-full rounded-xl border px-4 py-2">
    </label>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember"> Ghi nhớ đăng nhập</label>
    <button class="w-full rounded-full bg-brand py-2 font-semibold text-white">Đăng nhập</button>
    <p class="text-center text-sm">Chưa có tài khoản? <a class="text-brand underline" href="{{ route('register') }}">Đăng ký</a></p>
</form>
@endsection
