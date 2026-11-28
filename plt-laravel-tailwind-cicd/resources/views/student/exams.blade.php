@extends('layouts.student')
@section('title', 'Luyện Thi')
@section('sub', 'Luyện đề thi thử HSK 1 đến 6 theo từng cấp độ')
@section('icon', 'swords')
@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <section>
        <h2 class="sr-only">Các cấp độ</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($levels as $h => $n)
                <a href="{{ $n > 0 ? route('luyen-thi.lam', $h) : '#' }}" data-level="{{ $h }}"
                   class="block rounded-2xl border border-go bg-white p-4 {{ $n > 0 ? '' : 'pointer-events-none opacity-60' }}" @if ($n === 0) aria-disabled="true" @endif>
                    <p class="font-bold">HSK</p>
                    <p class="text-7xl font-bold text-go">{{ $h }}</p>
                    <p class="text-sm">{{ $n > 0 ? $n . ' câu hỏi' : 'Đề đang được biên soạn' }}</p>
                </a>
            @endforeach
        </div>
    </section>
    <section>
        <h2 class="text-xl font-bold">Chọn cấp độ thi thử</h2>
        <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($levels as $h => $n)
                @if ($n > 0)
                    <a href="{{ route('luyen-thi.lam', $h) }}" class="flex items-center justify-between rounded-2xl border bg-white px-4 py-3">
                        <span><b>Thi thử HSK{{ $h }}</b><br><span class="text-xs text-danger">Bắt đầu thi thử</span></span><x-icon name="arrow" class="size-7" />
                    </a>
                @endif
            @endforeach
        </div>
    </section>
</div>
@endsection
