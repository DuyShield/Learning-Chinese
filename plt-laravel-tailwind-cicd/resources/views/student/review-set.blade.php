@extends('layouts.student')
@section('title', 'Ôn Tập')
@section('sub', 'Củng cố kiến thức tiếng Trung')
@section('icon', 'clock')
@section('active', 'on-tap')
@section('content')
<div data-review class="mx-auto max-w-xl">
    <div class="rounded-2xl border bg-white p-6 text-center shadow-sm">
        <div class="mb-4 flex justify-between"><a href="{{ route('on-tap') }}" class="font-semibold">← Trở về</a><span class="font-bold" data-count>1/{{ $items->count() }}</span><span class="w-14"></span></div>
        <div data-face class="min-h-32"></div>
        <button type="button" data-flip class="mt-4 rounded-xl border px-6 py-2 font-semibold">Lật thẻ</button>
        <div data-actions hidden class="mt-4 flex justify-center gap-3 font-semibold">
            <button type="button" data-again-btn class="rounded-xl bg-salmon px-5 py-2">Cần ôn lại</button>
            <button type="button" data-known-btn class="rounded-xl bg-go px-5 py-2">Đã thuộc</button>
        </div>
    </div>
    <script type="application/json" id="review-data">@json($items)</script>
</div>
@endsection
