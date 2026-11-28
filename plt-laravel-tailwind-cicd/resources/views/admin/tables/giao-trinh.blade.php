<thead class="border-b-2 text-xs uppercase text-gray-600"><tr><th class="p-3">STT</th><th>Giáo trình</th><th>Mã</th><th>Cấp độ</th><th>Bài học</th><th>Từ vựng</th><th>Tác giả</th><th>Trạng thái</th><th></th></tr></thead>
<tbody>
@foreach ($rows as $r)
    <tr class="border-b" data-row><td class="p-3">{{ $rows->firstItem() + $loop->index }}</td>
        <td><b>{{ $r->name }}</b><br><span class="text-xs text-gray-500">{{ $r->description }}</span></td><td class="text-xs">{{ $r->code }}</td>
        <td><span class="rounded-full bg-brand/30 px-2 py-0.5 text-xs font-bold">HSK{{ $r->hsk }}</span></td><td>{{ $r->lessons }} bài</td><td>{{ number_format($r->words) }} từ</td><td>{{ $r->author }}</td>
        <td><span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $r->is_active ? 'bg-mint/60' : 'bg-salmon/50' }}">{{ $r->is_active ? 'Hoạt động' : 'Tạm dừng' }}</span></td>
    <td class="whitespace-nowrap px-3">
        <a href="{{ route('admin.edit', ['giao-trinh', $r->id]) }}" class="rounded border border-brand px-2 py-1 text-xs text-brand" aria-label="Sửa">✎</a>
        <form method="POST" action="{{ route('admin.destroy', ['giao-trinh', $r->id]) }}" class="inline" onsubmit="return confirm('Xóa mục này?')">@csrf @method('DELETE')<button class="rounded border border-red-400 px-2 py-1 text-xs text-red-500" aria-label="Xóa">🗑</button></form>
    </td>
    </tr>
@endforeach
</tbody>
