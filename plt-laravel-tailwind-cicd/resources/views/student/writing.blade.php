@extends('layouts.student')
@section('title', 'Luyện Viết Tiếng Trung')
@section('sub', 'Rèn luyện cách viết từng chữ Hán')
@section('icon', 'book')
@section('active', 'luyen-viet')
@section('content')
<div data-writing class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-[14rem_minmax(0,1fr)]">
    <aside class="rounded-2xl bg-white p-4 shadow-sm">
        <h2 class="text-xs font-bold tracking-wide text-gray-500">DANH SÁCH CHỮ</h2>
        <div class="mt-3 grid grid-cols-4 gap-2 lg:grid-cols-2">
            @foreach ($chars as $i => $c)
                <button type="button" data-pick="{{ $i }}" class="hanzi rounded-xl border py-2 text-2xl">{{ $c->c }}</button>
            @endforeach
        </div>
    </aside>

    <section class="space-y-4">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-lemon px-3 py-0.5 text-xs font-bold" data-hsk>HSK 1</span>
                <span class="text-sm text-gray-500" data-n>7 nét</span>
            </div>
            <div class="mt-3 flex flex-wrap items-center gap-6">
                <p class="hanzi text-8xl font-bold" data-char>你</p>
                <div>
                    <p class="text-2xl text-gray-500" data-py>nǐ</p>
                    <p class="text-lg" data-vi>bạn / anh / chị</p>
                    <button type="button" data-speak-char data-speak="你" class="mt-2 rounded-full border px-3 py-1 text-sm">🔊 Nghe</button>
                </div>
            </div>
            <div class="mt-5 flex gap-4 border-b text-sm font-medium">
                <button type="button" data-tabbtn="stroke" class="border-b-2 border-brand pb-2">Thứ tự nét</button>
                <button type="button" data-tabbtn="example" class="border-b-2 border-transparent pb-2">Ví dụ</button>
                <button type="button" data-tabbtn="info" class="border-b-2 border-transparent pb-2">Thông tin</button>
            </div>
            <p data-tabpanel="stroke" data-tab-stroke class="pt-3 text-sm text-gray-700"></p>
            <p data-tabpanel="example" data-tab-example hidden class="pt-3 text-sm text-gray-700"></p>
            <p data-tabpanel="info" data-tab-info hidden class="pt-3 text-sm text-gray-700"></p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="font-bold">Ô luyện tập</h2>
                <div class="flex gap-2 text-sm"><button type="button" data-hint class="rounded-full border px-3 py-1">Gợi ý</button><button type="button" data-clear class="rounded-full border px-3 py-1">Xóa</button></div>
            </div>
            <div class="relative mx-auto mt-3 aspect-square w-full max-w-sm rounded-xl border-2 border-dashed border-gray-300">
                <span data-hint-char aria-hidden="true" class="hanzi pointer-events-none absolute inset-0 grid place-items-center text-[14rem] leading-none text-gray-200 opacity-0">你</span>
                <canvas data-canvas width="640" height="640" aria-label="Ô viết chữ Hán" class="absolute inset-0 size-full touch-none"></canvas>
            </div>
            <p class="mt-2 text-center text-sm text-gray-500">Số nét đã vẽ: <b data-strokes>0</b></p>
            <p data-feedback role="status" class="mt-1 min-h-5 text-center text-sm font-medium text-brand"></p>
            <div class="mt-3 flex flex-wrap justify-center gap-3 text-sm font-semibold">
                <button type="button" data-rewrite class="rounded-xl bg-peach px-5 py-2">Viết lại</button>
                <button type="button" data-submit class="rounded-xl bg-go px-5 py-2">Nộp bài</button>
                <button type="button" data-next class="rounded-xl bg-brand px-5 py-2 text-white">Tiếp theo →</button>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex justify-between text-sm font-medium"><span>Tiến độ luyện tập hôm nay <b data-progress-text></b></span><span data-progress-pct>0%</span></div>
            <div class="mt-2 h-2 rounded-full bg-gray-200"><div data-progress-bar class="h-2 rounded-full bg-brand" style="width:0"></div></div>
            <dl class="mt-4 grid grid-cols-2 gap-3 text-center text-sm sm:grid-cols-5">
                <div class="rounded-xl bg-mint/50 p-2"><dd class="text-xl font-bold" data-done>0</dd><dt>Hoàn thành</dt></div>
                <div class="rounded-xl bg-salmon/40 p-2"><dd class="text-xl font-bold" data-left>0</dd><dt>Còn lại</dt></div>
                <div class="rounded-xl bg-lemon/60 p-2"><dd class="text-xl font-bold">1</dd><dt>Ngày liên tiếp</dt></div>
                <div class="rounded-xl bg-sky-100 p-2"><dd class="text-xl font-bold" data-points>0</dd><dt>Tổng điểm</dt></div>
                <div class="rounded-xl bg-violet-100 p-2"><dd class="text-xl font-bold" data-learned>0</dd><dt>Tổng đã học</dt></div>
            </dl>
        </div>
    </section>
    <script type="application/json" id="writing-data">@json($chars->map(fn ($c) => ['c' => $c->c, 'py' => $c->py, 'vi' => $c->vi, 'strokes' => $c->strokes, 'hsk' => $c->hsk, 'info' => $c->info, 'example' => $c->example])->values())</script>
</div>
@endsection
