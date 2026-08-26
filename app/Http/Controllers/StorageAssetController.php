<?php

namespace App\Http\Controllers;

use App\Models\SystemAsset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageAssetController extends Controller
{
    /**
     * Serve public storage files from database fallback or disk.
     */
    public function show(string $path): BinaryFileResponse|Response
    {
        // Sanitize path to prevent directory traversal
        $path = ltrim(str_replace(['../', '..\\'], '', $path), '/');

        // 1. Check if file is stored in database SystemAsset (ensures freshness)
        $asset = SystemAsset::where('key', $path)->first();
        if ($asset && $asset->data) {
            $rawContent = $asset->getRawContent();

            // Cache to local ephemeral storage if writable
            try {
                Storage::disk('public')->put($path, $rawContent);
            } catch (\Throwable $e) {
                // Ignore write errors in read-only environments
            }

            return response($rawContent, 200, [
                'Content-Type' => $asset->mime_type ?: 'image/png',
                'Content-Length' => strlen($rawContent),
                'Cache-Control' => 'public, max-age=60, stale-while-revalidate=600',
                'ETag' => md5($asset->updated_at ?? $path),
            ]);
        }

        // 2. Fallback: check if file exists on disk
        if (Storage::disk('public')->exists($path)) {
            $fullPath = Storage::disk('public')->path($path);
            $mimeType = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';

            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=60, stale-while-revalidate=600',
            ]);
        }

        abort(404, 'Asset tidak ditemukan.');
    }
}
