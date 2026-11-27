<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav class="sticky top-0 z-50 bg-sky-50/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-6 py-3 md:flex-nowrap">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-3">
                <img src="{{ asset('images/logoblue.png') }}" alt="" class="h-14 w-18">
                <span class="h-6 w-px bg-[#3E3B8A]/20"></span>
                <span class="inline-flex items-baseline gap-1.5 text-lg tracking-wide sm:text-[19px]">
                    <span class="font-bold text-[#3E3B8A]">Learning with</span>
                    <span class="font-extrabold tracking-tight text-[#2B2866]">Zimora</span>
                </span>
            </a>

            {{-- Nút mở toggle menu (chỉ hiện trên mobile) --}}
            <input type="checkbox" id="nav-toggle" class="peer sr-only">
            <label for="nav-toggle" aria-label="Mở menu"
                class="cursor-pointer rounded-lg px-3 py-2 text-2xl leading-none peer-focus-visible:outline-2 peer-focus-visible:outline-amber-600 md:hidden">
                ☰
            </label>

            <div class="hidden w-full flex-col gap-2 pt-2 peer-checked:flex md:flex md:flex-1 md:flex-row md:items-center md:justify-end md:gap-4 md:pt-0">
                {{-- Search box --}}
                <form action="{{ url('/tra-cuu') }}" method="GET" class="relative flex w-full items-center md:ml-6 md:w-72 lg:w-80">
                    <svg class="pointer-events-none absolute left-3 h-4 w-4 text-[#3E3B8A]/65" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>

                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Tìm từ vựng, pinyin, nghĩa..."
                        class="w-full rounded-lg border border-[#3E3B8A]/25 bg-white py-1.5 pl-9 pr-3.5 text-sm text-[#111B2E] placeholder-[#111B2E]/45 transition focus:border-[#3E3B8A] focus:outline-none focus:ring-2 focus:ring-[#3E3B8A]/15" />
                </form>

                {{-- Nút Đăng nhập nằm bên phải --}}
                @auth
                    <form action="{{ url('/logout') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit"
                            class="rounded-lg border border-[#3E3B8A]/20 bg-white px-5 py-2 text-sm font-semibold text-[#3E3B8A] transition hover:bg-[#3E3B8A]/5">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ url('/login') }}"
                        class="shrink-0 rounded-lg text-white bg-blue-500 px-5 py-2 text-center text-sm font-semibold transition hover:bg-sky-300">
                        Đăng nhập
                    </a>
                @endauth
            </div>

        </div>
    </nav>

</body>

</html>