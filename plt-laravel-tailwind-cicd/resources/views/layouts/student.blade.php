<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PLT') · PLT học tiếng Trung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+SC:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $nav = [
        ['trang-chu', 'home', 'Trang chủ'], ['luyen-viet', 'book', 'Luyện viết'], ['luyen-noi', 'mic', 'Luyện nói'],
        ['luyen-nghe', 'headphones', 'Luyện nghe'], ['tu-vung', 'stack', 'Từ vựng'], ['ngu-phap', 'cap', 'Ngữ pháp'],
        ['on-tap', 'brain', 'Ôn Tập'], ['luyen-thi', 'swords', 'Luyện thi'],
    ];
    $cur = trim($__env->yieldContent('active')) ?: request()->segment(1);
@endphp
<body class="bg-surface font-sans text-ink antialiased">
<div class="min-h-screen md:grid md:grid-cols-[14.5rem_minmax(0,1fr)]">
    <aside class="bg-brand md:sticky md:top-0 md:flex md:h-screen md:flex-col">
        <div class="px-4 py-3 text-center text-2xl font-bold md:border-b md:border-white/40 md:py-5">Logo</div>
        <nav aria-label="Menu chính" class="flex gap-1 overflow-x-auto px-2 pb-2 md:flex-1 md:flex-col md:overflow-visible md:px-3 md:pt-3">
            @foreach ($nav as [$path, $icon, $label])
                <a href="{{ route($path) }}" data-nav="{{ $path }}"
                   class="flex shrink-0 items-center gap-3 rounded-full border px-4 py-2 text-sm font-medium {{ $cur === $path ? 'border-white/70 bg-pill' : 'border-transparent hover:bg-white/20' }}"
                   @if ($cur === $path) aria-current="page" @endif>
                    <x-icon :name="$icon" /> {{ $label }}
                </a>
            @endforeach
        </nav>
        <div class="hidden space-y-3 border-t border-white/40 p-4 md:block">
            <div class="flex items-center justify-center gap-2 rounded-full bg-mint py-2 text-sm font-medium"><x-icon name="user" class="size-4" /> {{ auth()->user()->name ?? 'Profile User' }}</div>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="flex w-full items-center justify-center gap-2 rounded-full bg-salmon py-2 text-sm font-medium"><x-icon name="logout" class="size-4" /> Đăng xuất</button>
            </form>
        </div>
    </aside>

    <div class="min-w-0">
        <header class="flex items-center gap-3 border-b border-gray-200 bg-white px-6 py-3">
            <x-icon :name="trim($__env->yieldContent('icon')) ?: 'home'" class="size-8" />
            <div>
                <h1 class="text-2xl font-bold leading-tight">@yield('title')</h1>
                <p class="text-xs text-brand">@yield('sub')</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ml-auto md:hidden">@csrf
                <button class="rounded-full bg-salmon px-3 py-1 text-xs">Đăng xuất</button>
            </form>
        </header>
        <main class="p-4 md:p-8">@yield('content')</main>
    </div>
</div>
</body>
</html>
