@props(['active' => 'dashboard'])

@php
    $menu = [
        ['key' => 'dashboard', 'icon' => 'fa-house', 'text' => 'Trang chủ', 'href' => route('dashboard')],
        ['key' => 'listening', 'icon' => 'fa-headphones', 'text' => 'Luyện nghe', 'href' => '#'],
        ['key' => 'reading', 'icon' => 'fa-book-open', 'text' => 'Luyện đọc', 'href' => '#'],
        ['key' => 'writing', 'icon' => 'fa-pen-nib', 'text' => 'Luyện viết', 'href' => '#'],
        ['key' => 'speaking', 'icon' => 'fa-microphone', 'text' => 'Luyện nói', 'href' => '#'],
        ['key' => 'vocabulary', 'icon' => 'fa-layer-group', 'text' => 'Từ vựng', 'href' => route('vocabulary.user')],
        ['key' => 'grammar', 'icon' => 'fa-graduation-cap', 'text' => 'Ngữ pháp', 'href' => route('grammar.user')],
        ['key' => 'review', 'icon' => 'fa-brain', 'text' => 'Ôn tập', 'href' => '#'],
        ['key' => 'exams', 'icon' => 'fa-trophy', 'text' => 'Luyện thi', 'href' => '#'],
    ];
@endphp

<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo-icon">中</span>
        <span>Zimora</span>
    </a>
    {{-- Menu điều hướng --}}
    <nav class="menu" aria-label="menu">
        @foreach ($menu as $item)
            <a href="{{ $item['href'] }}" class="menu-item {{ $active === $item['key'] ? 'active' : '' }}"
                @if ($active === $item['key']) aria-current="page" @endif>
                <i class="fa-solid {{ $item['icon'] }}" aria-hidden="true"></i>
                <span>{{ $item['text'] }}</span>
            </a>
        @endforeach
    </nav>
    {{-- Thông tin người dùng --}}
    <div class="sidebar-bottom">
        <details class="user-menu">
            <summary class="user-box">
                <span class="avatar">
                    @if (auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="">
                    @else
                        <img src="{{ asset('images/logoblue.png') }}" alt="">
                    @endif
                </span>
                <span class="min-w-0">
                    <span class="user-name block truncate">{{ auth()->user()->full_name }}</span>
                </span>
                <i class="fa-solid fa-chevron-up ml-auto text-xs" aria-hidden="true"></i>
            </summary>
            <div class="user-submenu">
                <a href="{{ route('profile.edit') }}">
                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                    Hồ sơ cá nhân
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </details>
    </div>
</aside>
<div class="overlay" id="overlay"></div>
