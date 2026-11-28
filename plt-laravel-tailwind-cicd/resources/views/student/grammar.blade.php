@extends('layouts.student')
@section('title', 'Ngữ pháp tiếng Trung')
@section('sub', 'Làm chủ mọi điểm ngữ pháp')
@section('icon', 'cap')
@section('content')
<div data-grammar class="mx-auto max-w-5xl space-y-4">
    <nav aria-label="Cấp độ HSK" class="flex flex-wrap gap-1 rounded-2xl border bg-white p-2">
        @foreach ([1, 2, 3, 4, 5, 6] as $h)
            <a href="{{ route('ngu-phap', ['hsk' => $h]) }}" class="rounded-full px-5 py-1.5 text-sm font-bold {{ $h === $hsk ? 'bg-salmon' : '' }}">HSK{{ $h }}</a>
        @endforeach
        <a href="{{ route('ngu-phap', ['hsk' => 7]) }}" class="rounded-full px-5 py-1.5 text-sm font-bold {{ $hsk >= 7 ? 'bg-salmon' : '' }}">HSK7-9</a>
    </nav>
    <p class="text-xs text-gray-500">Nội dung bài học được biên soạn bởi PLT Solutions và thuộc sở hữu trí tuệ của PLT Solutions. Vui lòng không sao chép hoặc phân phối dưới mọi hình thức.</p>

    <div class="flex flex-wrap gap-2 text-xs font-bold">
        <button type="button" data-gfilter="all" class="rounded-full bg-salmon px-4 py-1">Tất cả</button>
        <button type="button" data-gfilter="new" class="rounded-full bg-white px-4 py-1">Chưa Học</button>
        <button type="button" data-gfilter="learning" class="rounded-full bg-white px-4 py-1">Đang Học</button>
        <button type="button" data-gfilter="review" class="rounded-full bg-white px-4 py-1">Đang Ôn Tập</button>
    </div>

    <form method="GET" action="{{ route('ngu-phap') }}" class="flex max-w-md items-center gap-2 rounded-full border bg-white px-4 py-1.5">
        <input type="hidden" name="hsk" value="{{ $hsk }}">
        <x-icon name="search" class="size-4" />
        <input name="q" value="{{ $q }}" placeholder="Tìm ngữ pháp theo tên hoặc pinyin..." class="min-w-0 flex-1 bg-transparent text-sm outline-none" aria-label="Tìm ngữ pháp">
    </form>

    @forelse ($items as $g)
        @if ($loop->first)<div class="grid gap-4 md:grid-cols-2">@endif
        <article data-gid="{{ $g->id }}" class="rounded-2xl border bg-white p-4">
            <div class="flex items-start justify-between gap-2">
                <h2><span class="hanzi text-2xl font-bold">{{ $g->zh }}</span> <span class="text-xl font-bold text-gray-400">{{ $g->py }}</span></h2>
                <span class="rounded-full bg-go px-3 text-xs font-bold">HSK{{ $g->hsk }}</span>
            </div>
            <p class="text-xs font-bold text-gray-500">{{ $g->name }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ $g->description }}</p>
            @if ($g->example)<p class="hanzi mt-2 text-sm">{{ $g->example }}</p>@endif
            <select data-status aria-label="Trạng thái học" class="mt-3 rounded-lg border px-2 py-1 text-xs">
                <option value="new">Chưa học</option><option value="learning">Đang học</option><option value="review">Đang ôn tập</option>
            </select>
        </article>
        @if ($loop->last)</div>@endif
    @empty
        <p class="rounded-2xl bg-white p-6" data-empty>Chưa có điểm ngữ pháp nào cho HSK{{ $hsk >= 7 ? '7-9' : $hsk }}{{ $q !== '' ? ' khớp “' . $q . '”' : '' }}. Nội dung đang được biên soạn.</p>
    @endforelse
</div>
@endsection
