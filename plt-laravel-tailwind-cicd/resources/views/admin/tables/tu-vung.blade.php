<thead class="border-b-2 text-xs uppercase text-gray-600"><tr><th class="p-3">STT</th><th>Hán từ</th><th>Nghĩa</th><th>Ví dụ</th><th>Cấp độ</th><th>Loại</th><th></th></tr></thead>
<tbody>
@foreach ($rows as $r)
    <tr class="border-b" data-row><td class="p-3">{{ $rows->firstItem() + $loop->index }}</td>
        <td><span class="hanzi font-bold">{{ $r->zh }}</span><br><span class="text-xs text-gray-500">{{ $r->py }}</span></td>
        <td>{{ $r->vi }}</td><td class="hanzi text-xs">{{ $r->example }}</td><td>HSK{{ $r->hsk }}</td><td>{{ $r->type }}</td>
    <td class="whitespace-nowrap px-3">
        <a href="{{ route('admin.edit', ['tu-vung', $r->id]) }}" class="rounded border border-brand px-2 py-1 text-xs text-brand" aria-label="Sửa">✎</a>
        <form method="POST" action="{{ route('admin.destroy', ['tu-vung', $r->id]) }}" class="inline" onsubmit="return confirm('Xóa mục này?')">@csrf @method('DELETE')<button class="rounded border border-red-400 px-2 py-1 text-xs text-red-500" aria-label="Xóa">🗑</button></form>
    </td>
    </tr>
@endforeach
</tbody>
