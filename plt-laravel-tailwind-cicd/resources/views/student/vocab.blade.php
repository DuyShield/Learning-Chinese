@extends('layouts.student')
@section('title', 'Từ vựng')
@section('sub', 'Nắm vững từ vựng tiếng Trung')
@section('icon', 'stack')
@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <form method="GET" action="{{ route('tu-vung') }}" class="flex items-center gap-2 rounded-full border bg-white px-4 py-1.5">
        <x-icon name="search" />
        <input name="q" value="{{ $q }}" placeholder="Tìm từ theo chữ Hán, pinyin hoặc nghĩa..." class="min-w-0 flex-1 bg-transparent text-sm outline-none" aria-label="Tìm từ">
        <button class="rounded-full bg-indigo-300 px-4 py-1 text-xs font-bold">Tra từ →</button>
    </form>

    @if ($word)
        <article class="rounded-2xl border bg-white p-6" data-word>
            <div class="flex flex-wrap items-center gap-3">
                <h2 class="hanzi text-4xl font-bold">{{ $word->zh }}</h2>
                <button type="button" data-speak="{{ $word->zh }}" aria-label="Nghe" class="text-2xl">🔊</button>
                <span class="text-2xl font-bold text-gray-400">{{ $word->py }}</span>
            </div>
            <p class="mt-2 font-semibold text-gray-500">{{ $word->vi }}</p>
            <h3 class="mt-4 font-bold">Định nghĩa</h3>
            <div class="mt-2 rounded-xl border p-4 text-sm">
                <p class="font-semibold">{{ $word->vi }}@if ($word->type) ({{ $word->type }})@endif</p>
                @if ($word->note)<p class="mt-1 text-gray-500">{{ $word->note }}</p>@endif
                @if ($word->example)
                    <p class="mt-3 font-semibold">Ví dụ:</p>
                    <p class="hanzi text-gray-600">{{ $word->example }}</p>
                    <p class="text-gray-600">{{ $word->example_vi }}</p>
                @endif
            </div>
            <a href="{{ route('tu-vung.luyen', ['hsk' => $word->hsk]) }}" class="mt-4 inline-block rounded-xl bg-go px-4 py-2 text-sm font-semibold">Luyện từ vựng HSK {{ $word->hsk }}</a>
        </article>
    @else
        <p class="rounded-2xl bg-white p-6" data-empty>Không tìm thấy từ “{{ $q }}”. Hãy thử chữ Hán, pinyin không dấu hoặc nghĩa tiếng Việt.</p>
    @endif

    <section>
        <h2 class="text-lg font-bold">Danh Sách Từ Gợi Ý</h2>
        <div class="mt-3 flex flex-wrap gap-3 rounded-2xl border bg-white p-4">
            @foreach ($suggestions as $s)
                <a href="{{ route('tu-vung', ['q' => $s->zh]) }}" class="hanzi rounded-full border px-4 py-1 text-lg">{{ $s->zh }}</a>
            @endforeach
        </div>
    </section>
</div>
@endsection
