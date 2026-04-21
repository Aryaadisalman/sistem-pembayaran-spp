<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;
        $currentMonthName = $now->isoFormat('MMMM');

        // Total siswa aktif
        $totalSiswa = Siswa::where('is_aktif', true)->count();

        // Pembayaran bulan ini (lunas)
        $pembayaranBulanIni = Pembayaran::where('status_pembayaran', 'lunas')
            ->whereMonth('tanggal_bayar', $currentMonth)
            ->whereYear('tanggal_bayar', $currentYear)
            ->count();

        // Menunggu validasi (pending)
        $menungguValidasi = Pembayaran::where('status_pembayaran', 'pending')->count();

        // Pembayaran ditolak
        $pembayaranDitolak = Pembayaran::where('status_pembayaran', 'ditolak')->count();

        // Siswa yang sudah bayar bulan ini (lunas)
        $paidStudentsCount = Pembayaran::where('status_pembayaran', 'lunas')
            ->whereMonth('tanggal_bayar', $currentMonth)
            ->whereYear('tanggal_bayar', $currentYear)
            ->distinct('siswa_id')
            ->count('siswa_id');

        // Siswa yang belum bayar
        $unpaidCount = $totalSiswa - $paidStudentsCount;
        $unpaidCount = $unpaidCount < 0 ? 0 : $unpaidCount;

        // Siswa lunas (sudah bayar minimal 1 SPP lunas)
        $siswaLunas = Pembayaran::where('status_pembayaran', 'lunas')
            ->distinct('siswa_id')
            ->count('siswa_id');

        // Siswa menunggak
        $siswaMenunggak = $totalSiswa - $siswaLunas;
        $siswaMenunggak = $siswaMenunggak < 0 ? 0 : $siswaMenunggak;

        return view('admin.dashboard', compact(
            'totalSiswa',
            'pembayaranBulanIni',
            'menungguValidasi',
            'pembayaranDitolak',
            'paidStudentsCount',
            'unpaidCount',
            'siswaLunas',
            'siswaMenunggak',
            'currentMonthName',
            'currentYear'
        ));
    }
}