<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $dailyWords = Vocabulary::query()
            ->inRandomOrder()
            ->limit(10)
            ->get(['hanzi', 'pinyin', 'meaning', 'hsk_level']);

        return view('pages/main', [
            'dailyWords' => $dailyWords,
        ]);
    }
}
