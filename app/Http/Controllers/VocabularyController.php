<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VocabularyController extends Controller
{
    // Tra từ vụng người dùng
    public function userIndex(): View
    {
        $goiY = Vocabulary::query()
            ->inRandomOrder()
            ->limit(8)
            ->get([
                'hanzi',
                'pinyin',
                'meaning',
                'part_of_speech',
                'hsk_level',
                'example_hanzi',
                'example_pinyin',
                'example_meaning',
            ]);

        $tuBanDau = $goiY->first();

        return view('pages.TraTuVung', [
            'goiY' => $goiY,
            'tuBanDau' => $tuBanDau,
        ]);
    }

    public function searchUser(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'q' => ['required', 'string', 'max:80'],
        ]);

        $results = Vocabulary::query()
            ->search(trim($filters['q']))
            ->orderBy('hanzi')
            ->limit(12)
            ->get([
                'hanzi',
                'pinyin',
                'meaning',
                'part_of_speech',
                'hsk_level',
                'example_hanzi',
                'example_pinyin',
                'example_meaning',
            ]);

        return response()->json($results);
    }

    // Tra từ vựng
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('vocabulary.user');
        }

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
        ]);
        $query = trim($filters['q'] ?? '');

        $results = Vocabulary::query()
            ->search($query)
            ->orderBy('hanzi')
            ->paginate(12)
            ->withQueryString();

        return view('pages.vocabulary-guest', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
