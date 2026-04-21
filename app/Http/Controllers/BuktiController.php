<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuktiController extends Controller
{
    public function show($filename)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            abort(403);
        }

        $path = 'bukti_bayar/' . $filename;

        // Cek apakah file ada
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}