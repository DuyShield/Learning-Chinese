<thead class="border-b-2 text-xs uppercase text-gray-600"><tr><th class="p-3">Mã</th><th>Người làm bài</th><th>Bài thi</th><th>Điểm</th><th>Kết quả</th><th>Ngày</th><th></th></tr></thead>
<tbody>
@foreach ($rows as $r)
    <tr class="border-b" data-row><td class="p-3">L{{ str_pad($r->id, 3, '0', STR_PAD_LEFT) }}</td><td class="font-semibold">{{ $r->user_name }}</td><td>{{ $r->exam }}</td><td>{{ $r->score }} đ</td>
        <td><span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $r->passed ? 'bg-mint/60' : 'bg-salmon/50' }}">{{ $r->passed ? 'Đạt' : 'Không đạt' }}</span></td><td>{{ $r->taken_at->format('d/m/Y') }}</td>
        <td class="whitespace-nowrap px-3"><form method="POST" action="{{ route('admin.destroy', ['lich-su', $r->id]) }}" class="inline" onsubmit="return confirm('Xóa mục này?')">@csrf @method('DELETE')<button class="rounded border border-red-400 px-2 py-1 text-xs text-red-500" aria-label="Xóa">🗑</button></form></td></tr>
@endforeach
</tbody>
