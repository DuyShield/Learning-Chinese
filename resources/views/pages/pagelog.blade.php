@php
    $p = config('auth_pages.' . $page);
    $prefill = isset($request) ? ['email' => $request->email] : [];
    $inputClass = fn ($name) => 'mt-2 w-full rounded-xl border bg-white px-4 py-3 pr-12 outline-none transition focus:ring-2 '
        . ($errors->has($name) ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-200 focus:border-[#3E3B8A] focus:ring-[#3E3B8A]/15');
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $p['title'] }} - Zimora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F7F8FC] font-sans text-[#111B2E] antialiased">
<main class="grid min-h-screen lg:grid-cols-[1fr_1fr]">
    <section class="relative hidden overflow-hidden bg-blue-500 lg:flex lg:flex-col">
        <div class="absolute -left-28 -top-24 h-96 w-96 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-36 right-[-6rem] h-[28rem] w-[28rem] rounded-full border-[70px] border-white/5"></div>
        <a href="{{ url('/') }}" class="relative z-10 flex items-center gap-3 px-12 pt-10">
            <img src="{{ asset('images/logowhite.png') }}" alt="Zimora" class="h-12">
            <span class="text-xl font-bold text-white">Zimora</span>
        </a>
        <div data-auth-carousel class="relative z-10 mx-auto my-auto w-full max-w-lg px-12 text-center text-white" aria-roledescription="carousel">
            <div class="min-h-[360px]">
                {{-- Slide 1 --}}
                <article data-carousel-slide>
                    <div class="mx-auto mb-8 flex h-44 w-44 items-center justify-center rounded-[3rem] bg-white/10 ring-1 ring-white/20 backdrop-blur">
                        <span class="text-[6.5rem] leading-none font-bold">好</span>
                    </div>
                    <h2 class="text-3xl font-bold">Một chữ mỗi ngày</h2>
                    <p class="mt-4 text-base leading-7 text-white/75">Xây dựng vốn từ vựng, luyện nghe và tiến gần hơn đến mục tiêu HSK.</p>
                </article>
                {{-- Slide 2 --}}
                <article data-carousel-slide hidden>
                    <div class="mx-auto mb-8 flex h-44 w-44 items-center justify-center rounded-[3rem] bg-amber-300/20 ring-1 ring-white/20 backdrop-blur">
                        <span class="text-[6.5rem] leading-none font-bold text-amber-100">学</span>
                    </div>
                    <h2 class="text-3xl font-bold">Học theo nhịp của bạn</h2>
                    <p class="mt-4 text-base leading-7 text-white/75">Bài học ngắn gọn giúp bạn duy trì thói quen tiếng Trung mỗi ngày.</p>
                </article>
                {{-- Slide 3 --}}
                <article data-carousel-slide hidden>
                    <div class="mx-auto mb-8 flex h-44 w-44 items-center justify-center rounded-[3rem] bg-sky-300/20 ring-1 ring-white/20 backdrop-blur">
                        <span class="text-[6.5rem] leading-none font-bold text-sky-100">梦</span>
                    </div>
                    <h2 class="text-3xl font-bold">Chạm gần hơn đến HSK</h2>
                    <p class="mt-4 text-base leading-7 text-white/75">Từng từ mới hôm nay sẽ đưa bạn đến gần mục tiêu ngày mai.</p>
                </article>
            </div>
            {{-- Dots --}}
            <div class="mx-auto mt-8 flex justify-center gap-2">
                <button type="button" data-carousel-dot="0" aria-label="Hiện slide 1" aria-current="true" class="h-1.5 w-10 rounded-full bg-white transition-all"></button>
                <button type="button" data-carousel-dot="1" aria-label="Hiện slide 2" aria-current="false" class="h-1.5 w-2 rounded-full bg-white/35 transition-all"></button>
                <button type="button" data-carousel-dot="2" aria-label="Hiện slide 3" aria-current="false" class="h-1.5 w-2 rounded-full bg-white/35 transition-all"></button>
            </div>
        </div>
    </section>
    {{-- Form Hiển thị --}}
    <section class="flex min-h-screen flex-col px-5 py-6 sm:px-10 lg:px-16">
        <a href="{{ url('/') }}" class="flex items-center gap-2 self-end text-sm font-semibold text-[#3E3B8A] lg:hidden">
            <img src="{{ asset('images/logoblue.png') }}" alt="" class="h-9">
            Zimora
        </a>
        <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center py-8">
            <a href="{{ url('/') }}" class="mb-10 hidden items-center gap-2 text-sm font-semibold text-slate-500 lg:flex">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Về trang chủ
            </a>
            <h1 class="text-3xl font-bold tracking-tight sm:text-[2.15rem]">{{ $p['title'] }}</h1>
            @if (!empty($p['subheading']))
                <p class="mt-3 text-sm leading-6 text-slate-500">{{ $p['subheading'] }}</p>
            @endif

            @if (session('status'))
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @isset($p['action'])
                <form action="{{ url($p['action']) }}" method="POST" class="mt-8 space-y-5" novalidate>
                    @csrf
                    @if (!empty($p['token']))
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    @endif
                    {{-- Fields hiển thị --}}
                    @foreach ($p['fields'] as $f)
                        @php $type = $f['type'] ?? 'text'; @endphp
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="{{ $f['name'] }}" class="text-sm font-semibold">{{ $f['label'] }}</label>
                                @isset($f['aside'])
                                    <a href="{{ url($f['aside']['href']) }}" class="text-sm font-semibold text-[#4F46A8] hover:text-[#312E81]">{{ $f['aside']['label'] }}</a>
                                @endisset
                            </div>
                            <div class="relative">
                                <input id="{{ $f['name'] }}" name="{{ $f['name'] }}" type="{{ $type }}"
                                    @if ($type !== 'password') value="{{ old($f['name'], $prefill[$f['name']] ?? '') }}" @endif
                                    @isset($f['autocomplete']) autocomplete="{{ $f['autocomplete'] }}" @endisset
                                    @if (!empty($f['autofocus'])) autofocus @endif
                                    @if ($f['required'] ?? true) required @endif
                                    class="{{ $inputClass($f['name']) }}">
                                @if ($type === 'password')
                                    <button type="button" data-password-toggle="{{ $f['name'] }}"
                                        class="absolute right-3 top-1/2 mt-1 -translate-y-1/2 rounded-md p-1 text-slate-400 hover:text-[#3E3B8A]"
                                        aria-label="Hiện hoặc ẩn mật khẩu">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                @endif
                            </div>
                            @error($f['name'])
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    @if ($page === 'login')
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 accent-[#3E3B8A]">
                            Ghi nhớ đăng nhập
                        </label>
                    @endif

                    <button type="submit" class="mt-1 w-full rounded-xl bg-blue-500 px-6 py-3.5 font-semibold text-white shadow-lg shadow-[#3E3B8A]/20 transition hover:bg-blue-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#3E3B8A]">
                        {{ $p['submit'] }}
                    </button>
                </form>
            @endisset

            @isset($p['buttons'])
                <div class="mt-8 space-y-3">
                    @foreach ($p['buttons'] as $b)
                        <a href="{{ url($b['href']) }}" class="block w-full rounded-xl px-6 py-3.5 text-center ph font-semibold {{ !empty($b['outline']) ? 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' : 'bg-[#3E3B8A] text-white hover:bg-[#312E78]' }}">
                            {{ $b['label'] }}
                        </a>
                    @endforeach
                </div>
            @endisset

            @isset($p['footer'])
                <p class="mt-7 text-center text-sm text-slate-500">
                    {{ $p['footer']['text'] ?? '' }}
                    <a href="{{ url($p['footer']['href']) }}" class="font-semibold text-blue-500 hover:text-blue-300">{{ $p['footer']['label'] }}</a>
                </p>
            @endisset
        </div>
    </section>
</main>
</body>
</html>