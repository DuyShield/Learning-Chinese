@extends('layouts.admin')
@section('title', ($row ? 'Sửa' : 'Thêm mới') . ' · ' . $cfg['title'])
@section('sub', $cfg['sub'])
@section('content')
<form method="POST" action="{{ $row ? route('admin.update', [$cfg['key'], $row->id]) : route('admin.store', $cfg['key']) }}" class="max-w-xl space-y-4 rounded-2xl border bg-white p-6" data-form>
    @csrf
    @if ($row) @method('PUT') @endif
    @foreach ($cfg['fields'] as $f)
        @continue(($f['only'] ?? null) === 'create' && $row)
        @php $val = old($f['name'], $row ? $row->{$f['name']} : ($f['type'] === 'checkbox' ? true : '')); @endphp
        @if ($f['type'] === 'checkbox')
            <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="{{ $f['name'] }}" value="1" {{ $val ? 'checked' : '' }}> {{ $f['label'] }}</label>
        @else
            <label class="block text-sm font-medium">{{ $f['label'] }}
                @if ($f['type'] === 'select')
                    <select name="{{ $f['name'] }}" class="mt-1 w-full rounded-lg border px-3 py-2 font-normal">@foreach ($f['options'] as $o)<option value="{{ $o }}" {{ $val === $o ? 'selected' : '' }}>{{ $o }}</option>@endforeach</select>
                @elseif ($f['type'] === 'textarea')
                    <textarea name="{{ $f['name'] }}" rows="4" class="mt-1 w-full rounded-lg border px-3 py-2 font-normal">{{ $val }}</textarea>
                @else
                    <input name="{{ $f['name'] }}" type="{{ $f['type'] }}" value="{{ $f['type'] === 'password' ? '' : $val }}" class="mt-1 w-full rounded-lg border px-3 py-2 font-normal">
                @endif
            </label>
            @error($f['name'])<p class="-mt-3 text-xs text-red-600">{{ $message }}</p>@enderror
        @endif
    @endforeach
    <div class="flex gap-3"><button class="rounded-lg bg-brand px-5 py-2 font-bold text-white">Lưu</button><a href="{{ route('admin.list', $cfg['key']) }}" class="rounded-lg border px-5 py-2">Hủy</a></div>
</form>
@endsection
