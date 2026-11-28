<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PLT - Website học tiếng Trung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php

$features = [
['icon' => 'gamepad', 'title' => 'Trò chơi trắc nghiệm', 'tile' => 'bg-sky-100',
'desc' => 'Chương trình học được chia nhỏ thành các màn chơi, vừa chơi vừa nhớ từ vựng thật lâu.'],
['icon' => 'book-open', 'title' => 'Học tập toàn diện', 'tile' => 'bg-blue-100',
'desc' => 'Học tiếng Trung trên mọi phương diện: đọc, viết, nói, từ vựng và ngữ pháp.'],
['icon' => 'mic', 'title' => 'Luyện nói', 'tile' => 'bg-violet-100',
'desc' => 'Hoàn thiện phát âm và giúp việc nói tiếng Trung trở nên dễ dàng.'],
['icon' => 'pencil', 'title' => 'Luyện viết', 'tile' => 'bg-pink-100',
'desc' => 'Học viết chữ Hán đúng nét, đúng thứ tự, nhớ chữ nhanh hơn.'],
['icon' => 'headphones', 'title' => 'Luyện nghe', 'tile' => 'bg-amber-100',
'desc' => 'Hoàn thiện kỹ năng nghe và giúp việc nghe trở nên dễ dàng.'],
['icon' => 'clipboard-list', 'title' => 'Bài tập', 'tile' => 'bg-cyan-100',
'desc' => 'Đa dạng bài tập giúp luyện từ vựng, ngữ pháp, ôn thi và đánh giá năng lực.'],
];

$steps = [
['title' => 'Tạo tài khoản', 'desc' => 'Đăng ký chỉ với email, mất chưa đến một phút.'],
['title' => 'Chọn trình độ', 'desc' => 'Làm bài kiểm tra nhanh để biết bạn nên bắt đầu từ cấp nào.'],
['title' => 'Học mỗi ngày', 'desc' => 'Theo lộ trình gợi ý, mỗi ngày một ít, tiến bộ thấy rõ.'],
];

$levels = [
['name' => 'Sơ cấp', 'hsk' => 'HSK 1 - 2', 'bar' => 'w-1/3', 'bg' => 'bg-sky-100',
'desc' => 'Chào hỏi, số đếm, những từ vựng đầu tiên.'],
['name' => 'Trung cấp', 'hsk' => 'HSK 3 - 4', 'bar' => 'w-2/3', 'bg' => 'bg-blue-100',
'desc' => 'Giao tiếp hằng ngày, đọc đoạn văn ngắn.'],
['name' => 'Cao cấp', 'hsk' => 'HSK 5 - 6', 'bar' => 'w-full', 'bg' => 'bg-violet-100',
'desc' => 'Đọc báo, trình bày ý kiến, luyện thi chứng chỉ.'],
];

$reviews = [
['name' => 'Minh Anh', 'role' => 'Sinh viên năm 2', 'av' => 'bg-sky-200',
'text' => 'Mình nhớ mặt chữ nhanh hơn hẳn nhờ các trò chơi trắc nghiệm.'],
['name' => 'Quốc Bảo', 'role' => 'Nhân viên văn phòng', 'av' => 'bg-violet-200',
'text' => 'Mỗi ngày học 15 phút trước giờ đi làm, sau ba tháng đã nói được câu cơ bản.'],
['name' => 'Thu Hà', 'role' => 'Học sinh lớp 11', 'av' => 'bg-pink-200',
'text' => 'Phần luyện nghe có phát âm chuẩn, mình tự tin hơn khi nói chuyện.'],
];

$faqs = [
['q' => 'Tôi chưa biết gì về tiếng Trung có học được không?', 'a' => 'Được. Lộ trình sơ cấp bắt đầu từ phát âm và các nét chữ đầu tiên.'],
['q' => 'Website có miễn phí không?', 'a' => 'Bạn có thể đăng ký và học các bài mở đầu để trải nghiệm trước.'],
['q' => 'Mỗi ngày nên học bao lâu?', 'a' => 'Khoảng 15 đến 30 phút mỗi ngày là đủ để duy trì thói quen và nhớ lâu.'],
['q' => 'Học trên điện thoại được không?', 'a' => 'Được. Trang hiển thị tốt trên điện thoại, máy tính bảng và máy tính.'],
];
@endphp


<body class="bg-white font-sans text-slate-900 antialiased">

    @include('components.navbar')

    <header class="relative overflow-hidden bg-[linear-gradient(135deg,#e0f2fe,#eff6ff_45%,#fdf2f8)]">
        {{-- Vệt màu mờ làm nền --}}
        <div class="pointer-events-none absolute -left-24 top-0 h-80 w-80 rounded-full bg-sky-200/70 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute right-0 top-10 h-80 w-80 rounded-full bg-violet-200/60 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/3 h-72 w-72 rounded-full bg-pink-200/60 blur-3xl" aria-hidden="true"></div>
        {{-- Vệt màu mờ làm nền --}}
        <span class="absolute left-8 top-14 hidden h-9 w-2.5 -rotate-[25deg] rounded-full bg-amber-300 lg:block" aria-hidden="true"></span>
        <span class="absolute left-4 top-24 hidden h-2.5 w-8 -rotate-[10deg] rounded-full bg-amber-300 lg:block" aria-hidden="true"></span>
        {{-- Nội dung --}}
        <div class="relative mx-auto grid max-w-6xl items-center gap-14 px-6 py-14 lg:grid-cols-2 lg:py-24">
            {{-- Cột trái: nội dung --}}
            <div>
                <p class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-4 py-1.5 text-sm font-medium text-blue-700">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    Học tiếng Trung ngay trên trình duyệt
                </p>

                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                    Chinh phục HSK
                    <span class="block bg-[linear-gradient(90deg,#2563eb,#6366f1,#a855f7)] bg-clip-text text-transparent">
                        từng chữ một
                    </span>
                </h1>

                <p class="mt-6 max-w-lg text-base leading-relaxed text-slate-600 sm:text-lg">
                    Từ vựng, hội thoại, luyện nghe, luyện viết và tra chữ Hán trong một nơi.
                    Không cần cài app, tiến độ được lưu tự động.
                </p>

                {{-- Ô tra cứu --}}
                <form action="{{ url('/tra-cuu') }}" method="GET"
                    class="mt-8 flex max-w-xl items-center gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-lg shadow-blue-900/5 focus-within:ring-2 focus-within:ring-blue-300">
                    <svg class="ml-2 h-5 w-5 shrink-0 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                    <input type="text" name="q" placeholder="Tra chữ Hán, pinyin hoặc nghĩa (vd: 好)"
                        aria-label="Tra cứu chữ Hán"
                        class="min-w-0 flex-1 bg-transparent py-2 text-sm outline-none placeholder:text-slate-400 sm:text-base">
                    <button type="button"
                        class="hidden items-center gap-1.5 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 sm:inline-flex">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                        </svg>
                        Vẽ chữ
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Tra
                    </button>
                </form>

                {{-- Nút chính --}}
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ url('/register') }}"
                        class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Bắt đầu lộ trình
                    </a>
                    <a href="#lo-trinh"
                        class="rounded-xl border border-blue-500 bg-white/60 px-6 py-3 font-semibold text-blue-700 transition hover:bg-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Xem giáo trình
                    </a>
                </div>

                <ul class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-600">
                    @foreach (['HSK 1 - 6', 'Học trên trình duyệt', 'Tiến độ tự lưu'] as $point)
                    <li class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                        {{ $point }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Cột phải: chữ Hán ngẫu nhiên trong ngày --}}
            <div
                id="daily-hanzi"
                class="relative mx-auto w-full max-w-sm pb-8"
                data-daily-words="{{ json_encode($dailyWords, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) }}"
            >
                <div class="absolute inset-0 rotate-6 rounded-[2rem] bg-violet-200/70"></div>
                {{-- Chữ Hán --}}
                <div class="relative rounded-[2rem] bg-white p-8 shadow-2xl shadow-blue-900/10 ring-1 ring-slate-100">
                    <div class="flex items-center justify-between text-sm text-slate-500">
                        <span>Chữ Hán hôm nay</span>
                        <span id="daily-hanzi-hsk" class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                            {{ $dailyWords->isNotEmpty() ? ($dailyWords->first()->hsk_level ?? 'Từ vựng') : 'Từ vựng' }}
                        </span>
                    </div>
                    {{-- Phần hiển thị chữ hán --}}
                    <p id="daily-hanzi-character" class="mt-4 text-center text-8xl font-bold text-blue-600" lang="zh-CN" aria-live="polite">
                        {{ $dailyWords->isNotEmpty() ? $dailyWords->first()->hanzi : '—' }}
                    </p>
                    {{-- Phần hiển thị pinyin --}}
                    <p id="daily-hanzi-pinyin" class="text-center text-2xl font-semibold">
                        {{ $dailyWords->isNotEmpty() ? $dailyWords->first()->pinyin : '' }}
                    </p>
                    {{-- Phần hiển thị nghĩa --}}
                    <p id="daily-hanzi-meaning" class="mt-1 text-center text-slate-500">
                        {{ $dailyWords->isNotEmpty() ? $dailyWords->first()->meaning : 'Chưa có dữ liệu từ vựng.' }}
                    </p>

                    <div class="mt-6 flex justify-center gap-3">
                        <button
                            id="daily-hanzi-random"
                            type="button"
                            class="rounded-xl border border-blue-200 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                            @disabled($dailyWords->isEmpty()) >
                            Đổi chữ
                        </button>
                        {{-- Nút phát âm thanh --}}
                        <button
                            id="daily-hanzi-speak"
                            type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-amber-100 px-4 py-2.5 text-sm font-semibold text-amber-800 transition hover:bg-amber-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600"
                            @disabled($dailyWords->isEmpty())>
                            <span aria-hidden="true"><x-icon name="voice" class="h-8 w-8"></span>
                            Nghe phát âm
                        </button>
                    </div>
                    <p id="daily-hanzi-status" class="mt-3 min-h-5 text-center text-xs text-slate-500" aria-live="polite"></p>
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- Phần liệt kê các tính năng --}}
        <section id="tinh-nang" class="mx-auto max-w-6xl scroll-mt-16 px-6 py-16 md:py-24">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                    PLT là website học tiếng Trung
                    <span class="block bg-[linear-gradient(90deg,#2563eb,#6366f1,#a855f7)] bg-clip-text text-transparent">thú vị và hiệu quả nhất</span>
                </h2>
                <p class="mt-4 text-slate-600">Đủ bốn kỹ năng nghe, nói, đọc, viết cùng bài tập để bạn học đều tay.</p>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($features as $item)
                <article class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition hover:shadow-lg hover:shadow-blue-900/5">
                    <div class="{{ $item['tile'] }} flex h-14 w-14 items-center justify-center rounded-2xl text-3xl">
                        <x-icon :name="$item['icon']" class="h-8 w-8" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $item['desc'] }}</p>
                </article>
                @endforeach
            </div>
        </section>

        {{-- 3 BƯỚC BẮT ĐẦU --}}
        <section class="bg-[linear-gradient(180deg,#eff6ff,#f5f3ff)] py-16 md:py-24">
            <div class="mx-auto max-w-6xl px-6">
                <h2 class="max-w-xl text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">Bắt đầu học trong 3 bước</h2>
                <ol class="mt-10 grid gap-5 md:grid-cols-3">
                    @foreach ($steps as $i => $step)
                    <li class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-blue-100">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-lg font-bold text-white">{{ $i + 1 }}</span>
                        <h3 class="mt-5 text-lg font-bold">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $step['desc'] }}</p>
                    </li>
                    @endforeach
                </ol>
            </div>
        </section>

        {{-- LỘ TRÌNH --}}
        <section id="lo-trinh" class="mx-auto max-w-6xl scroll-mt-16 px-6 py-16 md:py-24">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">Lộ trình từ cơ bản đến nâng cao</h2>
                <p class="mt-4 text-slate-600">Đi từng cấp, mỗi cấp có từ vựng, bài nghe và bài tập riêng.</p>
            </div>
            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @foreach ($levels as $level)
                <div class="{{ $level['bg'] }} rounded-3xl p-7">
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-blue-700">{{ $level['hsk'] }}</span>
                    <h3 class="mt-5 text-2xl font-bold">{{ $level['name'] }}</h3>
                    <p class="mt-2 min-h-12 text-sm leading-relaxed text-slate-700">{{ $level['desc'] }}</p>
                    <div class="mt-6 h-2 rounded-full bg-white/70">
                        <div class="{{ $level['bar'] }} h-2 rounded-full bg-blue-600"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- Phần học viên nói gì --}}
        <section class="bg-[linear-gradient(180deg,#f5f3ff,#fdf2f8)] py-16 md:py-24">
            <div class="mx-auto max-w-6xl px-6">
                <h2 class="max-w-xl text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">Học viên nói gì về PLT</h2>
                <div class="mt-10 grid gap-5 md:grid-cols-3">
                    @foreach ($reviews as $r)
                    <figure class="flex flex-col justify-between rounded-3xl bg-white p-7 shadow-sm ring-1 ring-violet-100">
                        <blockquote class="leading-relaxed text-slate-700">“{{ $r['text'] }}”</blockquote>
                        <figcaption class="mt-6 flex items-center gap-3">
                            <span class="{{ $r['av'] }} flex h-11 w-11 items-center justify-center rounded-full font-bold text-slate-800">{{ mb_substr($r['name'], 0, 1) }}</span>
                            <span class="text-sm"><b class="block">{{ $r['name'] }}</b><span class="text-slate-500">{{ $r['role'] }}</span></span>
                        </figcaption>
                    </figure>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Phần các câu hỏi thường gặp --}}
        <section id="faq" class="mx-auto max-w-6xl scroll-mt-16 px-6 py-16 md:py-24">
            <div class="grid gap-10 lg:grid-cols-[1fr_1.6fr]">
                <div>
                    <h2 class="text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">Câu hỏi thường gặp</h2>
                    <p class="mt-4 text-slate-600">Chưa thấy câu trả lời bạn cần? Hãy đăng ký và hỏi trực tiếp trong lúc học.</p>
                </div>
                <div class="space-y-3">
                    @foreach ($faqs as $f)
                    <details class="group rounded-2xl bg-sky-50 px-6 py-4 ring-1 ring-sky-100 open:bg-white open:shadow-sm">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold">
                            {{ $f['q'] }}
                            <span class="text-2xl leading-none text-blue-600 transition group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $f['a'] }}</p>
                    </details>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Phần đăng ký tài khoản dưới footer --}}
        <section class="px-6 pb-16 md:pb-24">
            <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-6 rounded-[2rem] bg-[linear-gradient(120deg,#2563eb,#6366f1,#a855f7)] px-8 py-12 text-white md:flex-row md:items-center md:px-14">
                <div class="max-w-lg">
                    <h2 class="text-3xl font-extrabold leading-tight tracking-tight">Sẵn sàng học tiếng Trung chưa?</h2>
                    <p class="mt-3 text-blue-50">Tạo tài khoản và học bài đầu tiên ngay hôm nay.</p>
                </div>
                <a href="{{ url('/register') }}"
                    class="shrink-0 rounded-xl bg-white px-8 py-3.5 font-semibold text-blue-700 shadow-lg shadow-blue-900/20 transition hover:bg-blue-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    Đăng ký ngay
                </a>
            </div>
        </section>
    </main>
@endif
    @include('components.footer')

</body>

</html>