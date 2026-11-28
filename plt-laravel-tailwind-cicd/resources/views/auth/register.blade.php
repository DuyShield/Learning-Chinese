@extends('auth.shell')
@section('title', 'Đăng ký')
@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-4">@csrf
    <label class="block text-sm font-medium">Họ tên
        <input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border px-4 py-2">
    </label>
    <label class="block text-sm font-medium">Email
        <input name="email" type="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border px-4 py-2">
    </label>
    <label class="block text-sm font-medium">Mật khẩu (từ 6 ký tự)
        <input name="password" type="password" required class="mt-1 w-full rounded-xl border px-4 py-2">
    </label>
    <label class="block text-sm font-medium">Nhập lại mật khẩu
        <input name="password_confirmation" type="password" required class="mt-1 w-full rounded-xl border px-4 py-2">
    </label>
    <button class="w-full rounded-full bg-peach py-2 font-semibold">Đăng ký</button>
    <p class="text-center text-sm">Đã có tài khoản? <a class="text-brand underline" href="{{ route('login') }}">Đăng nhập</a></p>
</form>
@endsection
