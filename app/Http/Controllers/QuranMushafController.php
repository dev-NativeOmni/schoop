<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;

class QuranMushafController extends Controller
{
    public function index(): View
    {
        $config = [];
        $configPath = storage_path('app/quran_settings.json');
        if (File::exists($configPath)) {
            $config = json_decode(File::get($configPath), true) ?? [];
        }

        return view('quran.mushaf', compact('config'));
    }
}
