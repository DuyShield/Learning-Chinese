@extends('layouts.student')
@section('title', 'Ôn Tập')
@section('sub', 'Củng cố kiến thức tiếng Trung')
@section('icon', 'clock')
@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <dl data-review-stats data-total="{{ $total }}" class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-xl border bg-white p-3"><dd class="text-xl font-bold" data-known>0</dd><dt class="text-xs text-gray-500">Đã thuộc</dt></div>
        <div class="rounded-xl border bg-white p-3"><dd class="text-xl font-bold" data-again>0</dd><dt class="text-xs text-gray-500">Cần ôn lại</dt></div>
        <div class="rounded-xl border bg-white p-3"><dd class="text-xl font-bold" data-total>{{ $total }}</dd><dt class="text-xs text-gray-500">Tổng từ vựng</dt></div>
        <div class="rounded-xl border bg-white p-3"><dd class="text-xl font-bold" data-pct>0%</dd><dt class="text-xs text-gray-500">Tiến độ</dt></div>
    </dl>

    <section>
        <h2 class="text-xl font-bold">Bộ Ôn Tập</h2>
        <p class="text-xs text-brand">({{ count($sets) }} bộ thẻ)</p>
        <div class="mt-4 grid gap-5 sm:grid-cols-2">
            @foreach ($sets as $s)
                <article class="rounded-2xl border bg-white p-4">
                    <div class="hanzi grid h-36 place-items-center rounded-xl bg-gradient-to-br from-brand to-mint text-6xl font-bold text-white" aria-hidden="true">汉</div>
                    <h3 class="mt-2 font-bold">{{ $s['name'] }}</h3>
                    <p class="text-xs text-gray-500">{{ $s['count'] }} từ</p>
                    <a href="{{ route('on-tap.bo', $s['id']) }}" class="mt-2 block rounded-full bg-go py-1.5 text-center font-bold">Bắt Ôn Tập</a>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
