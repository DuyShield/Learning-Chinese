@extends('layouts.student')
@section('title', 'Thi thử HSK' . $hsk)
@section('sub', 'Chọn một đáp án cho mỗi câu rồi nộp bài')
@section('icon', 'swords')
@section('active', 'luyen-thi')
@section('content')
<form method="POST" action="{{ route('luyen-thi.nop', $hsk) }}" class="mx-auto max-w-2xl space-y-4" data-exam>@csrf
    @foreach ($questions as $q)
        <fieldset class="rounded-2xl border bg-white p-5">
            <legend class="font-bold"><span class="mr-1 text-brand">Câu {{ $loop->iteration }}.</span> {{ $q->question }}</legend>
            <div class="mt-3 grid gap-2 sm:grid-cols-2">
                @foreach ($q->options as $i => $opt)
                    <label class="cursor-pointer"><input type="radio" name="answers[{{ $q->id }}]" value="{{ $i }}" class="peer sr-only">
                        <span class="hanzi block rounded-xl border px-4 py-2 peer-checked:border-danger peer-checked:bg-peach">{{ $opt }}</span></label>
                @endforeach
            </div>
        </fieldset>
    @endforeach
    <button class="w-full rounded-xl bg-danger py-3 font-bold">Nộp bài</button>
</form>
@endsection
