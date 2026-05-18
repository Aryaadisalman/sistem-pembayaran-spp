<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function generatePdf($id)
    {
        $pembayaran = Pembayaran::with(['pembayaranDetail.spp', 'angsuranDu.spp', 'siswa.user'])->findOrFail($id);
        
        // Verifikasi bahwa pembayaran ini milik siswa yang sedang login atau user adalah admin
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'kepsek') {
            // Admin dan Kepsek dapat mengakses semua kwitansi
        } elseif (Auth::user()->role === 'siswa') {
            // Cek apakah user memiliki relasi siswa
            if (!Auth::user()->siswa) {
                abort(403, 'Data siswa tidak ditemukan.');
            }
            
            // Cek apakah pembayaran ini milik siswa yang sedang login
            if ($pembayaran->siswa_id !== Auth::user()->siswa->siswa_id) {
                abort(403, 'Anda tidak memiliki akses ke kwitansi ini.');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
        
        $siswa = $pembayaran->siswa;
        $user = $siswa->user;
        
        $data = [
            'pembayaran' => $pembayaran,
            'siswa' => $siswa,
            'user' => $user,
            'tanggal' => Carbon::now()->isoFormat('D MMMM Y'),
            'nomor_kwitansi' => 'INV-' . $pembayaran->pembayaran_id
        ];
        
        $pdf = PDF::loadView('receipts.payment', $data);
        $filename = 'kwitansi-pembayaran-' . $pembayaran->pembayaran_id . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}