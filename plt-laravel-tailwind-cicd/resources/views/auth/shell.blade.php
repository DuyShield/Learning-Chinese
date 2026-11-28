<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · PLT học tiếng Trung</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-surface p-4 font-sans text-ink">
<div class="w-full max-w-sm rounded-2xl bg-white p-8 shadow">
    <a href="{{ route('gioi-thieu') }}" class="text-sm text-brand">← Giới thiệu</a>
    <h1 class="mb-6 mt-2 text-2xl font-bold">@yield('title')</h1>
    @if ($errors->any())<p role="alert" class="mb-4 rounded-lg bg-salmon/40 px-3 py-2 text-sm">{{ $errors->first() }}</p>@endif
    @yield('content')
</div>
</body>
</html>
