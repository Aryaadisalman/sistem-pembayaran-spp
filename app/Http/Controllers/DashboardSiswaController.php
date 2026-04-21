<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardSiswaController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;

        // Jika bukan siswa atau data tidak ditemukan, redirect ke admin dashboard
        if (!$siswa) {
            return redirect()->route('admin.dashboard');
        }

        $siswaId = $siswa->siswa_id;

        $totalBayar = DB::table('pembayaran_detail')
            ->join('pembayaran', 'pembayaran_detail.pembayaran_id', '=', 'pembayaran.pembayaran_id')
            ->where('pembayaran_detail.siswa_id', $siswaId)
            ->where('pembayaran.status_pembayaran', 'lunas')
            ->sum('pembayaran_detail.jumlah_bayar');

        // --- Final Confirmed Logic (from dd output) ---
        $bulanNama = Carbon::now()->locale('id')->monthName; // "Juni"
        $currentYear = Carbon::now()->year; // 2025

        // The format is "SPP - Juni" and "2025/2026".
        // This query is now built based on the exact data format, using is_aktif = 1.
        $sppBulanIni = Spp::where('is_aktif', 1)
            ->where('nama', 'SPP - ' . $bulanNama)
            ->where('tahun_ajaran', 'LIKE', '%' . $currentYear . '%')
            ->first();

        $tagihanBulanIni = 0;
        $sudahBayarBulanIni = false;

        if ($sppBulanIni) {
            // A bill for the current month exists.
            $tagihanBulanIni = $sppBulanIni->nominal;

            // 2. Check if this specific bill has been paid by the student.
            $sudahBayarBulanIni = PembayaranDetail::where('pembayaran_detail.siswa_id', $siswaId)
                ->where('pembayaran_detail.spp_id', $sppBulanIni->spp_id)
                ->join('pembayaran', 'pembayaran_detail.pembayaran_id', '=', 'pembayaran.pembayaran_id')
                ->where('pembayaran.status_pembayaran', 'lunas')
                ->exists();
        }
        // If no bill is found for the month, the card will correctly show Rp0 and not be marked as 'Lunas'.
        
        // --- Existing logic for recent payments and notifications ---
        $recentPayments = Pembayaran::with(['pembayaranDetail' => function($query) {
                $query->with(['spp' => function($query) {
                    $query->select('spp_id', 'nama', 'nominal');
                }]);
            }])
            ->select(['pembayaran_id', 'siswa_id', 'total_bayar', 'total_tagihan', 'status_pembayaran', 'tanggal_bayar', 'created_at'])
            ->where('siswa_id', $siswaId)
            ->latest('tanggal_bayar')
            ->take(3)
            ->get();
            
        $notificationsQuery = Pembayaran::with(['pembayaranDetail' => function($query) {
                $query->with(['spp' => function($query) {
                    $query->select('spp_id', 'nama');
                }]);
            }])
            ->select(['pembayaran_id', 'siswa_id', 'total_bayar', 'status_pembayaran', 'updated_at'])
            ->where('siswa_id', $siswaId)
            ->whereIn('status_pembayaran', ['lunas', 'ditolak'])
            ->where('updated_at', '>=', Carbon::now()->subDays(30))
            ->latest('updated_at')
            ->take(2)
            ->get();
            
        $notifications = $notificationsQuery->map(function($payment) {
            $type = $payment->status_pembayaran === 'lunas' ? 'approved' : 'rejected';
            $icon = $payment->status_pembayaran === 'lunas' ? 'check-circle' : 'times-circle';
            $color = $payment->status_pembayaran === 'lunas' ? 'green' : 'red';
            
            $itemsText = '';
            if (count($payment->pembayaranDetail) > 1) {
                $itemsText = $payment->pembayaranDetail[0]->spp->nama . ' +' . (count($payment->pembayaranDetail) - 1) . ' lainnya';
            } elseif (count($payment->pembayaranDetail) == 1) {
                $itemsText = $payment->pembayaranDetail[0]->spp->nama;
            } else {
                $itemsText = 'Pembayaran';
            }
            
            return [
                'id' => $payment->pembayaran_id,
                'type' => $type,
                'icon' => $icon,
                'color' => $color,
                'title' => $payment->status_pembayaran === 'lunas' ? 'Pembayaran Disetujui' : 'Pembayaran Ditolak',
                'message' => $payment->status_pembayaran === 'lunas' 
                    ? "Pembayaran untuk {$itemsText} telah disetujui."
                    : "Pembayaran untuk {$itemsText} ditolak. Silakan periksa detail pembayaran.",
                'amount' => $payment->total_bayar,
                'time' => $payment->updated_at,
                'read' => false
            ];
        });

        $unreadCount = $notifications->count();

        return view('user.dashboard', [
            'siswa' => $siswa,
            'totalBayar' => $totalBayar,
            'recentPayments' => $recentPayments,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'tagihanBulanIni' => $tagihanBulanIni,
            'sudahBayarBulanIni' => $sudahBayarBulanIni
        ]);
    }
}