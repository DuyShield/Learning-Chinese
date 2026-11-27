@props(['active' => 'dashboard'])

@php
    $menu = [
        ['key' => 'dashboard', 'icon' => 'fa-house', 'text' => 'Trang chủ', 'href' => route('dashboard')],
        ['key' => 'writing', 'icon' => 'fa-pen-nib', 'text' => 'Luyện viết', 'href' => '#'],
        ['key' => 'speaking', 'icon' => 'fa-microphone', 'text' => 'Luyện nói', 'href' => '#'],
        ['key' => 'vocabulary', 'icon' => 'fa-layer-group', 'text' => 'Từ vựng', 'href' => route('vocabulary.user')],
        ['key' => 'grammar', 'icon' => 'fa-graduation-cap', 'text' => 'Ngữ pháp', 'href' => '#'],
        ['key' => 'review', 'icon' => 'fa-brain', 'text' => 'Ôn tập', 'href' => '#'],
        ['key' => 'exams', 'icon' => 'fa-trophy', 'text' => 'Luyện thi', 'href' => '#'],
    ];
@endphp

<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo-icon">中</span>
        <span>Zimora</span>
    </a>

    <nav class="menu" aria-label="Điều hướng thành viên">
        @foreach ($menu as $item)
            <a href="{{ $item['href'] }}" class="menu-item {{ $active === $item['key'] ? 'active' : '' }}"
                @if ($active === $item['key']) aria-current="page" @endif>
                <i class="fa-solid {{ $item['icon'] }}" aria-hidden="true"></i>
                <span>{{ $item['text'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="sidebar-bottom">
        <a href="{{ route('dashboard') }}" class="user-box">
            <div class="avatar"><i class="fa-solid fa-user" aria-hidden="true"></i></div>
            <div>
                <p class="user-name">{{ auth()->user()->full_name }}</p>
                <p class="user-sub">Tài khoản học viên</p>
            </div>
        </a>
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout w-full border-0 text-white">
                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                Đăng xuất
            </button>
        </form>
    </div>
</aside>
<div class="overlay" id="overlay"></div>
