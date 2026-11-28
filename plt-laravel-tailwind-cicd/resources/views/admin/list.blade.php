@extends('layouts.admin')
@section('title', $showTabs ? 'Danh Sách Quản Lý' : $cfg['title'])
@section('sub', $cfg['sub'])
@section('tools')
    <form method="GET" class="flex items-center gap-2 rounded-lg border bg-surface px-3 py-1.5">
        @if ($filter)<input type="hidden" name="f" value="{{ $filter }}">@endif
        <x-icon name="search" class="size-4" />
        <input name="q" value="{{ $q }}" placeholder="{{ $cfg['placeholder'] }}" aria-label="Tìm kiếm" class="w-44 bg-transparent text-sm outline-none">
    </form>
    @if ($cfg['creatable'])
        <a href="{{ route('admin.create', $cfg['key']) }}" class="rounded-lg bg-lemon px-4 py-1.5 text-sm font-bold">＋ Thêm mới</a>
    @endif
@endsection
@section('content')
<div class="space-y-4">
    <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" data-stats>
        @foreach ($stats as [$label, $value, $emoji, $bg])
            <div class="flex items-center gap-3 rounded-2xl border bg-white p-3"><span class="grid size-10 place-items-center rounded-lg {{ $bg }}" aria-hidden="true">{{ $emoji }}</span>
                <div><p class="text-xs text-gray-500">{{ $label }}</p><p class="text-xl font-bold">{{ $value }}</p></div></div>
        @endforeach
    </section>

    <nav aria-label="Bộ lọc" class="flex flex-wrap gap-2 text-xs font-semibold">
        @if ($showTabs)
            @foreach (['users' => '👥 Users', 'tu-vung' => '📖 Từ vựng', 'giao-trinh' => '📚 Giáo trình', 'ngu-phap' => '✏️ Ngữ pháp', 'lich-su' => '🕘 Lịch sử'] as $k => $label)
                <a href="{{ route('admin.list', $k) }}" class="rounded-full border px-4 py-1.5 {{ $k === 'users' ? 'bg-brand text-white' : 'bg-white' }}">{{ $label }}</a>
            @endforeach
        @elseif ($cfg['filter'] === 'hsk')
            <a href="{{ route('admin.list', ['kind' => $cfg['key'], 'q' => $q]) }}" class="rounded-full border px-4 py-1.5 {{ $filter ? 'bg-white' : 'bg-brand text-white' }}">Tất cả</a>
            @foreach (range(1, $cfg['key'] === 'giao-trinh' ? 6 : 3) as $h)
                <a href="{{ route('admin.list', ['kind' => $cfg['key'], 'f' => $h, 'q' => $q]) }}" class="rounded-full border px-4 py-1.5 {{ (string) $filter === (string) $h ? 'bg-brand text-white' : 'bg-white' }}">HSK{{ $h }}</a>
            @endforeach
        @elseif ($cfg['filter'] === 'ket-qua')
            @foreach ([null => 'Tất cả', 'dat' => 'Đạt', 'khong-dat' => 'Không đạt'] as $k => $label)
                <a href="{{ route('admin.list', array_filter(['kind' => $cfg['key'], 'f' => $k, 'q' => $q])) }}" class="rounded-full border px-4 py-1.5 {{ (string) $filter === (string) $k ? 'bg-brand text-white' : 'bg-white' }}">{{ $label }}</a>
            @endforeach
        @endif
    </nav>

    <div class="overflow-x-auto rounded-2xl border bg-white">
        <table class="w-full text-left text-sm" data-table="{{ $cfg['key'] }}">
            @include('admin.tables.' . $cfg['key'])
        </table>
        @if ($rows->isEmpty())<p class="p-6 text-center text-gray-500" data-empty>Không có dữ liệu phù hợp.</p>@endif
    </div>
    <div>{{ $rows->links() }}</div>
</div>
@endsection
