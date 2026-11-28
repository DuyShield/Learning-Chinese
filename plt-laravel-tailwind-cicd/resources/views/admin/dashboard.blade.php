@extends('layouts.admin')
@section('title', 'Dashboard')
@section('sub', 'Chào mừng trở lại, ' . auth()->user()->name . ' 👋')
@section('content')
@php
    $max = max(1, max($months));
    $stops = []; $acc = 0;
    $colors = ['#5b8df6', '#86f7d3', '#e4fa9a', '#ff8e8e'];
    foreach ($roles as $i => $r) { $stops[] = $colors[$i] . ' ' . $acc . '% ' . ($acc + $r['pct']) . '%'; $acc += $r['pct']; }
    if ($acc < 100) { $stops[] = '#e5e7eb ' . $acc . '% 100%'; }
@endphp
<div class="space-y-6">
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" data-stats>
        @foreach ($stats as $s)
            <div class="rounded-2xl border bg-white p-4">
                <span class="grid size-10 place-items-center rounded-xl {{ $s['color'] }}" aria-hidden="true">{{ $s['icon'] }}</span>
                <p class="mt-3 text-3xl font-bold">{{ number_format($s['value']) }}</p>
                <p class="text-sm text-gray-500">{{ $s['label'] }}</p>
            </div>
        @endforeach
    </section>

    <section class="grid gap-4 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <div class="rounded-2xl border bg-white p-5">
            <h2 class="font-bold">Người dùng theo tháng</h2>
            <p class="text-xs text-gray-500">Thống kê năm {{ now()->year }}</p>
            <div class="mt-4 flex h-44 items-end gap-2" role="img" aria-label="Biểu đồ người dùng đăng ký theo tháng">
                @foreach ($months as $m => $n)
                    <div class="flex flex-1 flex-col items-center justify-end gap-1">
                        <div class="w-full rounded-t {{ $m === now()->month ? 'bg-brand' : 'bg-indigo-200' }}" style="height: {{ max(4, round($n / $max * 100)) }}%" title="{{ $n }}"></div>
                        <span class="text-[10px] text-gray-500">T{{ $m }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-2xl border bg-white p-5">
            <h2 class="font-bold">Phân loại người dùng</h2>
            <p class="text-xs text-gray-500">Tổng quan vai trò</p>
            <div class="mx-auto mt-4 grid size-32 place-items-center rounded-full" style="background: conic-gradient({{ implode(', ', $stops) }})">
                <div class="grid size-24 place-items-center rounded-full bg-white text-center"><span class="font-bold">{{ $totalUsers }}<br><span class="text-xs font-normal text-gray-500">Tổng</span></span></div>
            </div>
            <ul class="mt-4 space-y-1 text-sm">
                @foreach ($roles as $i => $r)
                    <li class="flex items-center justify-between"><span><span class="mr-2 inline-block size-2.5 rounded-full" style="background: {{ $colors[$i] }}"></span>{{ $r['label'] }}</span><b>{{ $r['pct'] }}%</b></li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="rounded-2xl border bg-white p-5">
        <div class="flex items-center justify-between"><div><h2 class="font-bold">Người dùng gần đây</h2><p class="text-xs text-gray-500">{{ $recent->count() }} người dùng mới nhất</p></div>
            <a href="{{ route('admin.list', 'users') }}" class="rounded-full bg-brand px-4 py-1.5 text-xs font-bold text-white">Xem tất cả</a></div>
        <div class="mt-3 overflow-x-auto">
            <table class="w-full text-left text-sm" data-recent>
                <thead class="text-xs text-gray-500"><tr><th class="py-2">Tên</th><th>Email</th><th>Vai trò</th><th>Trạng thái</th><th>Ngày tham gia</th></tr></thead>
                <tbody>
                @foreach ($recent as $u)
                    <tr class="border-t"><td class="py-2 font-semibold">{{ $u->name }}</td><td>{{ $u->email }}</td>
                        <td><span class="rounded-full bg-mint/50 px-2 py-0.5 text-xs font-semibold">{{ $u->role }}</span></td>
                        <td>{{ $u->is_active ? 'Hoạt động' : 'Không hoạt động' }}</td><td>{{ $u->created_at?->format('d/m/Y') }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
