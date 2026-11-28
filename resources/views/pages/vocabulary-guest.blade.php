<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tra từ tiếng Trung - Zimora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F7FAFF] font-sans text-[#111B2E] antialiased">
    <header class="border-b border-slate-200/80 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 sm:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logoblue.png') }}" alt="" class="h-10">
                <span class="text-lg font-extrabold tracking-tight text-[#2B2866]">Zimora</span>
            </a>
            <div class="flex items-center gap-2 sm:gap-3">
                @guest
                    <a href="{{ url('/login') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 sm:px-4">Đăng nhập</a>
                    <a href="{{ url('/register') }}" class="rounded-xl bg-[#3E3B8A] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#312E78]">Tạo tài khoản</a>
                @else
                    <a href="{{ url('/') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 sm:px-4">Về trang chủ</a>
                @endguest
            </div>
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden bg-[linear-gradient(135deg,#e9f5ff,#f0efff_58%,#fff5ed)]">
            <div class="pointer-events-none absolute -right-20 -top-32 h-96 w-96 rounded-full bg-blue-200/40 blur-3xl"></div>
            <div class="relative mx-auto max-w-5xl px-5 py-14 text-center sm:px-8 sm:py-20">
                <span class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white/80 px-4 py-1.5 text-sm font-semibold text-blue-800">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    Từ điển Trung - Việt
                </span>
                <h1 class="mt-5 text-3xl font-extrabold tracking-tight sm:text-5xl">Tra nghĩa, hiểu chữ <span class="text-[#514CB2]">thật nhanh</span></h1>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">Tìm chữ Hán, pinyin hoặc nghĩa tiếng Việt để khám phá từ vựng và câu ví dụ.</p>

                <form action="{{ url('/tra-cuu') }}" method="GET" class="mx-auto mt-8 flex max-w-3xl items-center gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl shadow-indigo-900/5 focus-within:ring-4 focus-within:ring-indigo-100">
                    <svg class="ml-2 h-5 w-5 shrink-0 text-[#514CB2]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="search" name="q" value="{{ $query }}" placeholder="Ví dụ: 你好, nǐ hǎo, xin chào" aria-label="Từ cần tra"
                        class="min-w-0 flex-1 bg-transparent px-2 py-3 text-sm outline-none placeholder:text-slate-400 sm:text-base">
                    <button class="rounded-xl bg-[#3E3B8A] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#312E78] sm:px-7">Tra từ</button>
                </form>

                @if ($query === '')
                    <p class="mt-5 text-sm text-slate-500">Thử tìm: <a href="{{ url('/tra-cuu?q=你好') }}" class="font-semibold text-[#514CB2] hover:underline">你好</a>, <a href="{{ url('/tra-cuu?q=xièxie') }}" class="font-semibold text-[#514CB2] hover:underline">xièxie</a>, <a href="{{ url('/tra-cuu?q=cảm ơn') }}" class="font-semibold text-[#514CB2] hover:underline">cảm ơn</a></p>
                @endif
            </div>
        </section>

        <section class="mx-auto max-w-5xl px-5 py-10 sm:px-8">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#514CB2]">{{ $query !== '' ? 'Kết quả tra cứu' : 'Từ vựng phổ biến' }}</p>
                    <h2 class="mt-1 text-xl font-bold sm:text-2xl">{{ $query !== '' ? 'Tìm kiếm: “'.$query.'”' : 'Khám phá từ vựng HSK' }}</h2>
                </div>
                <span class="text-sm text-slate-500">{{ $results->total() }} kết quả</span>
            </div>

            @if ($results->isEmpty())
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                    <span class="text-4xl">🔎</span>
                    <h3 class="mt-4 text-lg font-bold">Chưa tìm thấy từ phù hợp</h3>
                    <p class="mt-2 text-sm text-slate-500">Thử một chữ Hán, pinyin hoặc nghĩa khác nhé.</p>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ($results as $word)
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-3xl font-bold text-[#3E3B8A]">{{ $word->hanzi }}</p>
                                    <p class="mt-1 text-sm font-medium text-slate-500">{{ $word->pinyin }}</p>
                                </div>
                                @if ($word->hsk_level)
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">{{ $word->hsk_level }}</span>
                                @endif
                            </div>
                            <p class="mt-4 text-base font-semibold">{{ $word->meaning }}</p>
                            @if ($word->part_of_speech)
                                <p class="mt-1 text-xs text-slate-400">{{ $word->part_of_speech }}</p>
                            @endif
                            @if ($word->example_hanzi)
                                <div class="mt-4 border-t border-slate-100 pt-3">
                                    <p class="text-sm font-medium">{{ $word->example_hanzi }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $word->example_pinyin }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $word->example_meaning }}</p>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
                <div class="mt-6">{{ $results->links() }}</div>
            @endif

            @guest
                <aside class="mt-10 flex flex-col gap-5 rounded-3xl bg-[#302D70] p-6 text-white sm:flex-row sm:items-center sm:justify-between sm:p-8">
                    <div>
                        <p class="text-lg font-bold">Muốn lưu từ và theo dõi tiến độ?</p>
                        <p class="mt-1 text-sm text-white/70">Tạo tài khoản để xây dựng bộ từ vựng cá nhân và ôn tập mỗi ngày.</p>
                    </div>
                    <a href="{{ url('/register') }}" class="shrink-0 rounded-xl bg-white px-5 py-3 text-center text-sm font-bold text-[#302D70] transition hover:bg-indigo-50">Bắt đầu miễn phí</a>
                </aside>
            @endguest
        </section>
    </main>
</body>
</html>
