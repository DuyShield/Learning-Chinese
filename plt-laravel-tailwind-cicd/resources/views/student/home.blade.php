@extends('layouts.student')
@section('title', 'Trang chủ')
@section('sub', 'Chào mừng bạn quay lại học tiếng Trung')
@section('icon', 'home')
@section('content')
<div data-home data-today="{{ $today->toDateString() }}" class="mx-auto max-w-6xl space-y-6">
    <h2 class="text-2xl font-bold">Chào {{ auth()->user()->name }}, hôm nay học gì nào?</h2>

    <section class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Chuỗi hiện tại</p>
            <p class="text-3xl font-bold" data-streak>0 ngày</p>
            <div class="mt-2 flex gap-3 text-sm"><span class="rounded-full bg-lemon px-3 py-0.5" data-level>Cấp 1</span><span class="rounded-full bg-mint px-3 py-0.5" data-xp>0 XP</span></div>
            <button data-checkin class="mt-4 w-full rounded-xl bg-danger px-4 py-2 font-semibold text-white disabled:bg-gray-300 disabled:text-gray-600">Điểm danh nhận 50 điểm hôm nay</button>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <h3 class="font-bold">Ôn tập nhanh</h3>
            <div class="mt-3 grid gap-2 text-sm font-medium">
                <a href="{{ route('tu-vung.luyen') }}" class="rounded-xl bg-peach px-4 py-2">Học từ vựng</a>
                <a href="{{ route('luyen-nghe') }}" class="rounded-xl bg-mint px-4 py-2">Nghe / Phát âm</a>
                <a href="{{ route('ngu-phap') }}" class="rounded-xl bg-violet-200 px-4 py-2">Học ngữ pháp</a>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <h3 class="font-bold">Lịch học</h3>
            <div class="mt-2 grid grid-cols-7 gap-1 text-center text-xs">
                @foreach (['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'] as $d)<span class="text-gray-500">{{ $d }}</span>@endforeach
                @for ($i = 1; $i < $monthStart->dayOfWeekIso; $i++)<span></span>@endfor
                @for ($d = 1; $d <= $monthStart->daysInMonth; $d++)
                    @php $date = $monthStart->copy()->day($d); @endphp
                    <span data-date="{{ $date->toDateString() }}" class="rounded-md py-1 {{ $date->isSameDay($today) ? 'ring-2 ring-danger' : 'bg-surface' }}">{{ $d }}</span>
                @endfor
            </div>
            <p class="mt-3 text-sm">Tổng kết tháng {{ $today->month }}: đã điểm danh <b data-month-total>0</b> ngày</p>
        </div>
    </section>

    <section>
        <h3 class="text-lg font-bold">Bài học nổi bật hôm nay</h3>
        <div class="mt-3 grid gap-4 sm:grid-cols-3">
            @foreach ($highlights as $w)
                <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">
                    <div><p class="hanzi text-2xl font-bold">{{ $w->zh }}</p><p class="text-sm text-gray-500">{{ $w->py }} · {{ $w->vi }}</p></div>
                    <button type="button" data-speak="{{ $w->zh }}" aria-label="Nghe {{ $w->zh }}" class="rounded-full bg-go p-3"><x-icon name="volume" /></button>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
