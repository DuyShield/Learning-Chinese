<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') · PLT Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+SC:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $kind = request()->route('kind');
    $groups = ['users' => ['Danh sách users', '👥'], 'tu-vung' => ['Danh sách từ vựng', '📖'], 'giao-trinh' => ['Danh sách giáo trình', '📚'], 'ngu-phap' => ['Danh sách ngữ pháp', '✏️'], 'lich-su' => ['Lịch sử kiểm tra', '🕘']];
@endphp
<body class="bg-surface font-sans text-ink antialiased">
<div class="min-h-screen md:grid md:grid-cols-[14.5rem_minmax(0,1fr)]">
    <aside class="bg-brand md:sticky md:top-0 md:flex md:h-screen md:flex-col md:overflow-y-auto">
        <div class="px-4 py-3 text-center text-2xl font-bold md:border-b md:border-white/40 md:py-5">Logo</div>
        <nav aria-label="Menu quản trị" class="px-3 pb-2 md:flex-1 md:pt-3">
            <a href="{{ route('admin.dashboard') }}" data-nav="dashboard"
               class="block rounded-full border px-4 py-2 font-medium {{ request()->routeIs('admin.dashboard') ? 'border-white/70 bg-pill' : 'border-transparent' }}">🏠 Trang chủ</a>
            <details open class="mt-2">
                <summary class="cursor-pointer rounded-full px-4 py-2 font-medium {{ request()->routeIs('admin.quan-ly') ? 'bg-pill' : '' }}">📋 Danh Sách Quản Lý</summary>
                <ul class="mt-1 space-y-1 pl-4 text-sm">
                    @foreach ($groups as $k => [$label, $emoji])
                        <li><a href="{{ route('admin.list', $k) }}" data-nav="{{ $k }}"
                               class="block rounded-lg px-3 py-1.5 {{ $kind === $k && ! request()->routeIs('admin.quan-ly') ? 'bg-white/60 font-semibold' : '' }}">{{ $emoji }} {{ $label }}</a></li>
                    @endforeach
                </ul>
            </details>
        </nav>
        <div class="hidden space-y-3 border-t border-white/40 p-4 md:block">
            <div class="rounded-full bg-mint py-2 text-center font-medium">👤 {{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="w-full rounded-full bg-salmon py-2 font-medium">🚪 Đăng xuất</button>
            </form>
        </div>
    </aside>

    <div class="min-w-0">
        <header class="flex flex-wrap items-center gap-3 border-b border-gray-200 bg-white px-6 py-3">
            <div class="mr-auto">
                <h1 class="text-xl font-bold leading-tight">@yield('title')</h1>
                <p class="text-xs text-gray-500">@yield('sub')</p>
            </div>
            @yield('tools')
        </header>
        @if (session('ok'))<div role="status" class="mx-6 mt-4 rounded-lg bg-mint/50 px-4 py-2 text-sm" data-flash>{{ session('ok') }}</div>@endif
        @if ($errors->any())<div role="alert" class="mx-6 mt-4 rounded-lg bg-salmon/40 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif
        <main class="p-4 md:p-6">@yield('content')</main>
    </div>
</div>
</body>
</html>
