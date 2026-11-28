<thead class="border-b-2 text-xs uppercase text-gray-600"><tr><th class="p-3">STT</th><th>Ngữ pháp</th><th>Giải thích</th><th>Cấp độ</th><th></th></tr></thead>
<tbody>
@foreach ($rows as $r)
    <tr class="border-b" data-row><td class="p-3">{{ $rows->firstItem() + $loop->index }}</td>
        <td><span class="hanzi font-bold">{{ $r->zh }}</span> <span class="text-xs text-gray-500">{{ $r->py }}</span><br><span class="text-xs">{{ $r->name }}</span></td>
        <td class="max-w-sm text-xs text-gray-600">{{ \Illuminate\Support\Str::limit($r->description, 90) }}</td><td>HSK{{ $r->hsk }}</td>
    <td class="whitespace-nowrap px-3">
        <a href="{{ route('admin.edit', ['ngu-phap', $r->id]) }}" class="rounded border border-brand px-2 py-1 text-xs text-brand" aria-label="Sửa">✎</a>
        <form method="POST" action="{{ route('admin.destroy', ['ngu-phap', $r->id]) }}" class="inline" onsubmit="return confirm('Xóa mục này?')">@csrf @method('DELETE')<button class="rounded border border-red-400 px-2 py-1 text-xs text-red-500" aria-label="Xóa">🗑</button></form>
    </td>
    </tr>
@endforeach
</tbody>
