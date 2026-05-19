<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\Siswa;
use App\Models\Spp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function pembayaran(Request $request)
    {
        $query = Pembayaran::with([
            'siswa:siswa_id,nama,nis,kelas', 
            'pembayaranDetail' => function($query) {
                $query->with(['spp:spp_id,nama,nominal']);
            },
            'angsuranDu' => function($query) {
                $query->with(['spp:spp_id,nama,nominal']);
            },
        ])
        ->select([
            'pembayaran_id', 'siswa_id', 'total_bayar', 'total_tagihan', 
            'status_pembayaran', 'tanggal_bayar', 'created_at', 'tahun_ajaran'
        ])
        ->where('status_pembayaran', 'lunas');
        
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_bayar', '>=', $request->dari_tanggal);
        }
        
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_bayar', '<=', $request->sampai_tanggal);
        }
        
        $pembayaran = $query->latest('tanggal_bayar')->get()
            ->sortBy(function($p) {
                return [$p->siswa->kelas ?? '', $p->siswa->nama ?? ''];
            })->values();
        
        if ($request->download) {
            $pdf = Pdf::loadView('admin.laporan.pembayaran_pdf', compact('pembayaran'));
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'laporan-pembayaran.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        }
        
        return view('admin.laporan.pembayaran', compact('pembayaran'));
    }
    
    public function siswaLunas(Request $request)
    {
        $kelasList = Siswa::select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');
            
        // Get all active SPPs for the filter dropdown
        $sppList = Spp::where('is_aktif', true)
            ->orderBy('nama')
            ->get(['spp_id', 'nama', 'tahun_ajaran', 'nominal']);
            
        $query = Siswa::with('user:user_id,email')
            ->select(['siswa_id', 'user_id', 'nama', 'nis', 'kelas', 'tanggal_masuk'])
            ->where('is_aktif', true);
            
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }
        
        $siswa = $query->orderBy('kelas')->orderBy('nama')->get();
        
        // Default to all active SPPs if no specific SPP is selected
        if ($request->filled('spp_id')) {
            $activeSpps = [$request->spp_id];
            $selectedSpp = $sppList->where('spp_id', $request->spp_id)->first();
        } else {
            $activeSpps = $sppList->pluck('spp_id')->toArray();
            $selectedSpp = null;
        }
        
        // Get all students who have paid all selected SPPs
        $siswaLunas = [];
        
        // Debug information
        $debug = [
            'siswa_count' => count($siswa),
            'active_spps' => count($activeSpps),
            'kelas_filter' => $request->kelas,
            'spp_filter' => $request->spp_id ? ($selectedSpp ? $selectedSpp->nama : 'SPP #' . $request->spp_id) : 'Semua SPP'
        ];
        
        foreach ($siswa as $s) {
            // Get all paid SPPs for this student
            $paidSppIds = PembayaranDetail::join('pembayaran', 'pembayaran_detail.pembayaran_id', '=', 'pembayaran.pembayaran_id')
                ->where('pembayaran_detail.siswa_id', $s->siswa_id)
                ->where('pembayaran.status_pembayaran', 'lunas')
                ->pluck('pembayaran_detail.spp_id')
                ->toArray();
                
            // If there are no active SPPs selected, consider all students as paid
            if (empty($activeSpps)) {
                $siswaLunas[] = $s;
                continue;
            }
            
            // If we're filtering by one SPP, we only care if that specific SPP is paid
            if ($request->filled('spp_id')) {
                $hasPaid = in_array($request->spp_id, $paidSppIds);
                if ($hasPaid) {
                    $siswaLunas[] = $s;
                }
            } else {
                // Otherwise check if all active SPPs are paid
                $unpaidSpps = array_diff($activeSpps, $paidSppIds);
                if (empty($unpaidSpps)) {
                    $siswaLunas[] = $s;
                }
            }
            
            // Add debug info for this student
            $s->debug = [
                'paid_spps' => $paidSppIds,
                'is_fully_paid' => $request->filled('spp_id') ? 
                    in_array($request->spp_id, $paidSppIds) : 
                    empty(array_diff($activeSpps, $paidSppIds))
            ];
        }
        
        // Urutkan per kelas lalu per nama alfabet
        usort($siswaLunas, function($a, $b) {
            if ($a->kelas === $b->kelas) {
                return strcmp($a->nama, $b->nama);
            }
            return strcmp($a->kelas, $b->kelas);
        });

        // Kelompokkan per kelas
        $siswaLunasByKelas = [];
        foreach ($siswaLunas as $s) {
            $siswaLunasByKelas[$s->kelas][] = $s;
        }

        if ($request->download) {
            $pdf = Pdf::loadView('admin.laporan.siswa_lunas_pdf', [
                'siswa' => $siswaLunas,
                'siswaByKelas' => $siswaLunasByKelas,
                'kelas' => $request->kelas ?? 'Semua',
                'spp' => $selectedSpp ? $selectedSpp->nama : 'Semua SPP'
            ]);
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'laporan-siswa-lunas.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        }
        
        return view('admin.laporan.siswa_lunas', [
            'siswa' => $siswaLunas,
            'siswaByKelas' => $siswaLunasByKelas,
            'kelasList' => $kelasList,
            'sppList' => $sppList,
            'debug' => $debug
        ]);
    }
    
    public function siswaMenunggak(Request $request)
    {
        $kelasList = Siswa::select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');
            
        // Get all active SPPs for the filter dropdown
        $sppList = Spp::where('is_aktif', true)
            ->orderBy('nama')
            ->get(['spp_id', 'nama', 'tahun_ajaran', 'nominal']);
            
        $query = Siswa::with('user:user_id,email')
            ->select(['siswa_id', 'user_id', 'nama', 'nis', 'kelas', 'tanggal_masuk'])
            ->where('is_aktif', true);
            
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }
        
        $siswa = $query->orderBy('kelas')->orderBy('nama')->get();
        
        // Get all active SPPs
        $activeSpps = Spp::where('is_aktif', true);
        
        // Option to filter by specific SPP item
        if ($request->filled('spp_id')) {
            $activeSpps = $activeSpps->where('spp_id', $request->spp_id);
        }
        
        // Get SPP items
        $activeSpps = $activeSpps->get();
        $activeSppIds = $activeSpps->pluck('spp_id')->toArray();
        
        // Get all students who have not paid all selected SPPs
        $siswaMenunggak = [];
        $tunggakanDetails = [];
        
        foreach ($siswa as $s) {
            $paidSppIds = PembayaranDetail::join('pembayaran', 'pembayaran_detail.pembayaran_id', '=', 'pembayaran.pembayaran_id')
                ->where('pembayaran_detail.siswa_id', $s->siswa_id)
                ->where('pembayaran.status_pembayaran', 'lunas')
                ->pluck('pembayaran_detail.spp_id')
                ->toArray();
                
            $unpaidSppIds = array_diff($activeSppIds, $paidSppIds);
            
            // If we're filtering by a specific SPP, check if it's unpaid
            if ($request->filled('spp_id')) {
                $isUnpaid = in_array($request->spp_id, $unpaidSppIds);
                if ($isUnpaid) {
                    $siswaMenunggak[] = $s;
                    
                    // Get details of unpaid SPPs
                    $unpaidSpps = $activeSpps->whereIn('spp_id', $unpaidSppIds);
                    $totalTunggakan = $unpaidSpps->sum('nominal');
                    
                    $tunggakanDetails[$s->siswa_id] = [
                        'items' => $unpaidSpps,
                        'total' => $totalTunggakan
                    ];
                }
            } 
            // Otherwise check if there are any unpaid SPPs
            else if (!empty($unpaidSppIds)) {
                $siswaMenunggak[] = $s;
                
                // Get details of unpaid SPPs
                $unpaidSpps = $activeSpps->whereIn('spp_id', $unpaidSppIds);
                $totalTunggakan = $unpaidSpps->sum('nominal');
                
                $tunggakanDetails[$s->siswa_id] = [
                    'items' => $unpaidSpps,
                    'total' => $totalTunggakan
                ];
            }
        }
        
        // Urutkan per kelas lalu per nama alfabet
        usort($siswaMenunggak, function($a, $b) {
            if ($a->kelas === $b->kelas) {
                return strcmp($a->nama, $b->nama);
            }
            return strcmp($a->kelas, $b->kelas);
        });

        // Kelompokkan per kelas
        $siswaMenunggakByKelas = [];
        foreach ($siswaMenunggak as $s) {
            $siswaMenunggakByKelas[$s->kelas][] = $s;
        }

        if ($request->download) {
            $pdf = Pdf::loadView('admin.laporan.siswa_menunggak_pdf', [
                'siswa' => $siswaMenunggak,
                'siswaByKelas' => $siswaMenunggakByKelas,
                'tunggakanDetails' => $tunggakanDetails,
                'kelas' => $request->kelas ?? 'Semua',
                'spp' => $request->filled('spp_id') ? 
                    $sppList->where('spp_id', $request->spp_id)->first()->nama : 'Semua'
            ]);
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'laporan-siswa-menunggak.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        }
        
        return view('admin.laporan.siswa_menunggak', [
            'siswa' => $siswaMenunggak,
            'siswaByKelas' => $siswaMenunggakByKelas,
            'tunggakanDetails' => $tunggakanDetails,
            'kelasList' => $kelasList,
            'sppList' => $sppList,
        ]);
    }
    
    public function laporanHarian(Request $request)
    {
        $query = Pembayaran::with([
                'siswa:siswa_id,nama,nis,kelas',
                'pembayaranDetail' => function($q) {
                    $q->with(['spp:spp_id,nama,nominal']);
                },
                'angsuranDu' => function($q) {
                    $q->with(['spp:spp_id,nama,nominal']);
                },
            ])
            ->select([
                'pembayaran_id', 'siswa_id', 'total_bayar', 'total_tagihan',
                'status_pembayaran', 'tanggal_bayar', 'tahun_ajaran', 'keterangan'
            ])
            ->where('status_pembayaran', 'lunas');

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_bayar', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_bayar', '<=', $request->sampai_tanggal);
        }

        $semuaPembayaran = $query->latest('tanggal_bayar')->get();

        // Kelompokkan per hari & hitung total per hari
        $perHari = $semuaPembayaran
            ->groupBy(function($p) {
                return Carbon::parse($p->tanggal_bayar)->format('Y-m-d');
            })
            ->map(function($items, $tanggal) {
                return [
                    'tanggal'     => $tanggal,
                    'label'       => Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y'),
                    'pembayaran'  => $items,
                    'total'       => $items->sum('total_bayar'),
                    'jumlah'      => $items->count(),
                ];
            })
            ->sortKeysDesc()
            ->values();

        $grandTotal     = $semuaPembayaran->sum('total_bayar');
        $totalTransaksi = $semuaPembayaran->count();

        if ($request->download) {
            $pdf = Pdf::loadView('admin.laporan.harian_pdf', compact(
                'perHari', 'grandTotal', 'totalTransaksi',
            ))->setPaper('a4', 'portrait');

            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, 'laporan-harian-' . now()->format('Y-m-d') . '.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        }

        return view('admin.laporan.harian', compact(
            'perHari', 'grandTotal', 'totalTransaksi'
        ));
    }
}