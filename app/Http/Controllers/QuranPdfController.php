<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class QuranPdfController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('quran.mushaf');
    }

    public function updateConfig(Request $request): RedirectResponse
    {
        $request->validate([
            'drive_link' => 'required|string',
        ]);

        $link = $request->input('drive_link');
        $fileId = null;

        // Parse File ID from Google Drive URL
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $link, $matches)) {
            $fileId = $matches[1];
        } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $link, $matches)) {
            $fileId = $matches[1];
        } else {
            // Assume the input itself is the ID if no match is found
            $fileId = trim($link);
        }

        if (empty($fileId)) {
            return back()->with('error', 'Format Link Google Drive tidak dikenali.');
        }

        try {
            $configPath = storage_path('app/quran_settings.json');
            File::ensureDirectoryExists(storage_path('app'));
            File::put($configPath, json_encode([
                'google_drive_id' => $fileId,
                'google_drive_link' => $link,
            ]));

            return back()->with('success', 'Mushaf Google Drive berhasil dihubungkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan konfigurasi: '.$e->getMessage());
        }
    }
}
