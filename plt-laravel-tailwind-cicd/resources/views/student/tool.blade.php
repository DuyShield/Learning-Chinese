@extends('layouts.student')
@section('title', $tool['title'])
@section('sub', $tool['sub'])
@section('icon', $tool['icon'])
@section('active', $tool['route'] === 'luyen-ngu-phap' ? 'ngu-phap' : $tool['route'])
@section('content')
<h2 class="mb-4 text-3xl font-bold">{{ $tool['heading'] }}</h2>
<form method="GET" action="{{ route($tool['route'] . '.bai') }}" class="space-y-6 rounded-2xl bg-[#dfe6ff] p-5" data-tool-form>
    <fieldset>
        <legend class="mb-2 font-semibold">{{ $tool['modeLabel'] }}</legend>
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach ($tool['modes'] as $key => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="mode" value="{{ $key }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                    <span class="block rounded-xl bg-white py-4 text-center font-semibold peer-checked:bg-peach peer-checked:ring-2 peer-checked:ring-danger peer-focus-visible:ring-2">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </fieldset>

    <fieldset>
        <legend class="mb-2 font-semibold">Cấp độ</legend>
        <div class="flex flex-wrap gap-3">
            @foreach (range(1, 6) as $h)
                <label class="cursor-pointer"><input type="radio" name="hsk" value="{{ $h }}" class="peer sr-only" {{ $h === 1 ? 'checked' : '' }}>
                    <span class="block rounded-lg bg-white px-5 py-2 text-sm font-semibold peer-checked:bg-peach peer-checked:ring-2 peer-checked:ring-danger">HSK {{ $h }}</span></label>
            @endforeach
        </div>
    </fieldset>

    @if ($tool['topic'])
        <label class="block font-semibold">Chủ đề
            <select name="topic" class="mt-2 block w-full max-w-sm rounded-lg border bg-white px-3 py-2 font-normal">
                <option value="">Tất cả chủ đề</option>
                @foreach ($topics as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
            </select>
        </label>
    @endif

    @if ($tool['count'])
        <fieldset>
            <legend class="mb-2 font-semibold">Số lượng từ</legend>
            <div class="flex flex-wrap gap-3">
                @foreach ([10, 20, 30, 40] as $n)
                    <label class="cursor-pointer"><input type="radio" name="count" value="{{ $n }}" class="peer sr-only" {{ $n === 10 ? 'checked' : '' }}>
                        <span class="block rounded-lg bg-white px-5 py-2 text-sm font-semibold peer-checked:bg-peach peer-checked:ring-2 peer-checked:ring-danger">{{ $n }} từ</span></label>
                @endforeach
            </div>
        </fieldset>
    @endif

    <button class="mx-auto block w-full max-w-xl rounded-xl bg-danger py-3 font-bold">{{ $tool['start'] }}</button>
</form>
@endsection
