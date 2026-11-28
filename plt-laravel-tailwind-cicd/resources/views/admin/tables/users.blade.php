<thead class="border-b-2 text-xs uppercase text-gray-600"><tr><th class="p-3">Mã</th><th>Người dùng</th><th>Email</th><th>Vai trò</th><th>Điện thoại</th><th>Trạng thái</th><th></th></tr></thead>
<tbody>
@foreach ($rows as $r)
    <tr class="border-b" data-row><td class="p-3">U{{ str_pad($r->id, 3, '0', STR_PAD_LEFT) }}</td><td class="font-semibold">{{ $r->name }}</td><td>{{ $r->email }}</td>
        <td><span class="rounded-full bg-mint/50 px-2 py-0.5 text-xs font-semibold">{{ $r->role }}</span></td><td>{{ $r->phone }}</td><td>{{ $r->is_active ? 'Hoạt động' : 'Không hoạt động' }}</td>
    <td class="whitespace-nowrap px-3">
        <a href="{{ route('admin.edit', ['users', $r->id]) }}" class="rounded border border-brand px-2 py-1 text-xs text-brand" aria-label="Sửa">✎</a>
        <form method="POST" action="{{ route('admin.destroy', ['users', $r->id]) }}" class="inline" onsubmit="return confirm('Xóa mục này?')">@csrf @method('DELETE')<button class="rounded border border-red-400 px-2 py-1 text-xs text-red-500" aria-label="Xóa">🗑</button></form>
    </td>
    </tr>
@endforeach
</tbody>
