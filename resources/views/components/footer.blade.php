<footer class="border-t border-[#3E3B8A]/15 bg-sky-50/95 text-[#111B2E]">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-12 lg:grid-cols-[1.6fr_1fr]">

        {{-- Cột 1: Thương hiệu --}}
        <div>
            <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                <img src="{{ asset('images/logoblue.png') }}" alt="PLT Solutions" class="h-12 w-auto">
                <span class="h-6 w-px bg-[#3E3B8A]/20"></span>
                <span class="inline-flex items-baseline gap-1.5 text-lg tracking-wide sm:text-[19px]">
                    <span class="font-bold text-[#3E3B8A]">Learning with</span>
                    <span class="font-extrabold tracking-tight text-[#2B2866]">Zimora</span>
                </span>
            </div>

            <p class="mt-5 max-w-xl text-sm leading-relaxed text-[#111B2E]/75">
                Hệ thống học tiếng Trung, ôn luyện kiến thức và thi trắc nghiệm trực tuyến thông minh.
                Được phát triển bởi PLT SOLUTIONS nhằm đem lại trải nghiệm giáo dục số hiện đại,
                trực quan và hiệu quả.
            </p>

            <div class="mt-5 flex gap-3">
                <a href="#" aria-label="Facebook"
                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-[#3E3B8A]/15 bg-white text-[#3E3B8A] shadow-2xs transition hover:border-[#3E3B8A] hover:bg-[#3E3B8A] hover:text-[#FAF8EC]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a href="#" aria-label="X"
                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-[#3E3B8A]/15 bg-white text-[#3E3B8A] shadow-2xs transition hover:border-[#3E3B8A] hover:bg-[#3E3B8A] hover:text-[#FAF8EC]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Cột 2: Hỗ trợ --}}
        <div class="lg:justify-self-end">
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-[#2B2866]">Hỗ trợ</h3>
            <ul class="mt-4 space-y-3.5 text-sm">
                <li>
                    <a href="{{ url('/lien-he') }}" class="inline-flex items-center gap-2 font-semibold text-[#3E3B8A] transition hover:text-[#2B2866]">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3" />
                        </svg>
                        Liên hệ hỗ trợ
                    </a>
                </li>
                <li>
                    <a href="{{ url('/dieu-khoan') }}" class="text-[#111B2E]/75 transition hover:text-[#3E3B8A]">
                        Điều khoản sử dụng
                    </a>
                </li>
                <li>
                    <a href="{{ url('/chinh-sach-bao-mat') }}" class="text-[#111B2E]/75 transition hover:text-[#3E3B8A]">
                        Chính sách bảo mật
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-[#3E3B8A]/10">
        <p class="mx-auto max-w-6xl px-6 py-4 text-sm text-[#111B2E]/60">
            &copy; {{ date('Y') }} PLT Solutions &amp; Zimora. Bảo lưu mọi quyền.
        </p>
    </div>
</footer>