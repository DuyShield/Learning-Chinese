<?php

namespace App\Http\Controllers;

use App\Models\GrammarPoint;
use App\Models\UserGrammarProgress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrammarController extends Controller
{
    /**
     * Tải toàn bộ ngữ pháp, ví dụ và tiến độ của người dùng hiện tại.
     * NguPhap.js dùng grammar_id trên URL để mở đúng modal chi tiết.
     */
    public function index(Request $request): View
    {
        $grammarPoints = GrammarPoint::query()
            ->with([
                'examples' => fn ($query) => $query->orderBy('order_index'),
                'progress' => fn ($query) => $query->where('user_id', $request->user()->id),
            ])
            ->orderBy('hsk_level')
            ->orderBy('order_index')
            ->get();

        return view('pages.part_of_speech', [
            'grammarPoints' => $grammarPoints,
        ]);
    }

    /**
     * Cập nhật trạng thái hợp lệ cho điểm ngữ pháp của người dùng hiện tại.
     */
    public function updateProgress(Request $request, GrammarPoint $grammarPoint): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['chua-hoc', 'dang-hoc', 'on-tap'])],
        ]);

        $progress = UserGrammarProgress::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'grammar_point_id' => $grammarPoint->id,
            ],
            ['status' => $data['status']],
        );

        return response()->json(['status' => $progress->status]);
    }
}
