@extends('layouts.student')
@section('title', 'Kết quả thi thử HSK' . $hsk)
@section('sub', 'Xem lại các câu đã làm')
@section('icon', 'swords')
@section('active', 'luyen-thi')
@section('content')
<div class="mx-auto max-w-2xl space-y-4">
    <div class="rounded-2xl bg-white p-6 text-center">
        <p class="text-sm text-gray-500">Điểm của bạn</p>
        <p class="text-6xl font-extrabold" data-score>{{ $score }}</p>
        <p class="mt-1" data-summary>Đúng {{ $correct }}/{{ $total }} câu</p>
        <p class="mt-2 inline-block rounded-full px-4 py-1 font-bold {{ $score >= 60 ? 'bg-go' : 'bg-salmon' }}" data-verdict>{{ $score >= 60 ? 'Đạt' : 'Chưa đạt' }}</p>
    </div>
    @foreach ($review as $r)
        <div class="rounded-2xl border bg-white p-4 {{ $r['ok'] ? 'border-go' : 'border-salmon' }}">
            <p class="font-semibold">Câu {{ $loop->iteration }}. {{ $r['q']->question }}</p>
            <p class="text-sm">Bạn chọn: <span class="hanzi">{{ $r['picked'] === null ? 'Chưa trả lời' : $r['q']->options[$r['picked']] }}</span></p>
            @unless ($r['ok'])<p class="text-sm text-green-700">Đáp án đúng: <span class="hanzi">{{ $r['q']->options[$r['q']->answer] }}</span></p>@endunless
        </div>
    @endforeach
    <div class="flex gap-3"><a href="{{ route('luyen-thi') }}" class="rounded-xl bg-brand px-5 py-2 font-semibold text-white">Về Luyện thi</a><a href="{{ route('luyen-thi.lam', $hsk) }}" class="rounded-xl border px-5 py-2 font-semibold">Làm lại</a></div>
</div>
@endsection
