@extends('layouts.student')
@section('title', $tool['title'])
@section('sub', $tool['sub'])
@section('icon', $tool['icon'])
@section('active', ($tool['route'] ?? 'tu-vung') === 'luyen-ngu-phap' ? 'ngu-phap' : ($tool['route'] ?? 'tu-vung'))
@section('content')
<div data-practice class="mx-auto max-w-2xl">
    <div class="relative rounded-2xl border bg-white p-6 text-center shadow-sm md:p-10" data-card>
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ $config['back'] }}" class="font-semibold">← Trở về</a>
            <span class="font-bold" data-count>1/{{ count($config['items']) }}</span>
            <span class="w-14"></span>
        </div>
        <div data-body>
            @if (count($config['items']) === 0)<p>Chưa có nội dung cho lựa chọn này.</p>@endif
        </div>
        <p data-msg role="status" class="mt-4 min-h-5 text-sm"></p>
        <div data-next-wrap hidden class="mt-4"><button type="button" data-next class="rounded-xl bg-brand px-6 py-2 font-semibold text-white">Câu tiếp theo</button></div>
    </div>
    <script type="application/json" id="practice-config">@json($config)</script>
</div>
@endsection
