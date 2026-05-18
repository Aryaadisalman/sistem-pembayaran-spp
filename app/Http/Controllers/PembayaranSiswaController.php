<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spp;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranSiswaController extends Controller
{
    public function create()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Data siswa tidak ditemukan. Hubungi admin.');
        }
        
        // Ambil hanya SPP dan PPDB - DU dihandle terpisah via AngsuranDuController
        $allActiveSpps = Spp::where('is_aktif', true)
            ->whereIn('jenis', ['spp', 'ppdb'])
            ->select(['spp_id', 'nama', 'nominal', 'tahun_ajaran', 'jenis'])
            ->get()
            ->keyBy('spp_id');
        
        if ($allActiveSpps->isEmpty()) {
            return redirect()->route('pembayaran.history')
                ->with('error', 'Tidak ada SPP aktif saat ini.');
        }
        
        // SPP yang sudah lunas - tidak perlu dibayar lagi
        $paidSppIds = DB::table('pembayaran_detail')
            ->join('pembayaran', 'pembayaran_detail.pembayaran_id', '=', 'pembayaran.pembayaran_id')
            ->where('pembayaran_detail.siswa_id', $siswa->siswa_id)
            ->where('pembayaran.status_pembayaran', 'lunas')
            ->pluck('pembayaran_detail.spp_id')
            ->toArray();

        // SPP yang sedang pending - tidak ditampilkan agar tidak double submit
        $pendingSppIds = DB::table('pembayaran_detail')
            ->join('pembayaran', 'pembayaran_detail.pembayaran_id', '=', 'pembayaran.pembayaran_id')
            ->where('pembayaran_detail.siswa_id', $siswa->siswa_id)
            ->where('pembayaran.status_pembayaran', 'pending')
            ->pluck('pembayaran_detail.spp_id')
            ->toArray();

        // Gabungkan: sembunyikan yang lunas dan yang pending
        $excludedSppIds = array_unique(array_merge($paidSppIds, $pendingSppIds));
        
        $activeSpps = $allActiveSpps->whereNotIn('spp_id', $excludedSppIds)->values();
        
        $notificationsQuery = Pembayaran::with(['pembayaranDetail' => function($query) {
                $query->with(['spp' => function($query) {
                    $query->select('spp_id', 'nama');
                }]);
            }])
            ->select(['pembayaran_id', 'siswa_id', 'total_bayar', 'status_pembayaran', 'updated_at'])
            ->where('siswa_id', $siswa->siswa_id)
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
        
        // Ambil tagihan DU aktif beserta info angsuran siswa
        $duItems = \App\Models\Spp::where('is_aktif', true)
            ->where('jenis', 'du')
            ->select(['spp_id', 'nama', 'nominal', 'tahun_ajaran', 'jenis', 'max_angsuran'])
            ->get()
            ->map(function($du) use ($siswa) {
                $angsuranLunas = \App\Models\AngsuranDu::where('siswa_id', $siswa->siswa_id)
                    ->where('spp_id', $du->spp_id)
                    ->where('status', 'lunas')
                    ->count();
                $angsuranPending = \App\Models\AngsuranDu::where('siswa_id', $siswa->siswa_id)
                    ->where('spp_id', $du->spp_id)
                    ->where('status', 'pending')
                    ->count();
                $nominalPerAngsuran = $du->max_angsuran > 0 ? round($du->nominal / $du->max_angsuran) : $du->nominal;
                $du->angsuran_lunas   = $angsuranLunas;
                $du->angsuran_pending = $angsuranPending;
                $du->angsuran_ke      = $angsuranLunas + $angsuranPending + 1;
                $du->nominal_angsuran = $nominalPerAngsuran;
                $du->sudah_lunas      = $angsuranLunas >= $du->max_angsuran;
                $du->bisa_bayar       = $angsuranPending === 0 && $angsuranLunas < $du->max_angsuran;
                return $du;
            });

        return view('user.pembayaran.create', compact('activeSpps', 'duItems', 'notifications', 'unreadCount'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'spp_ids' => 'nullable|array',
            'spp_ids.*' => 'nullable|exists:spp,spp_id',
            'ppdb_ids' => 'nullable|array',
            'ppdb_ids.*' => 'nullable|exists:spp,spp_id',
            'du_ids' => 'nullable|array',
            'du_ids.*' => 'nullable|exists:spp,spp_id',
            'total_bayar' => 'required|numeric|min:1',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:255',
        ]);
        
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Data siswa tidak ditemukan. Hubungi admin.');
        }
        
        $allSppIds = [];
        if ($request->has('spp_ids') && is_array($request->spp_ids)) {
            $allSppIds = array_merge($allSppIds, $request->spp_ids);
        }
        if ($request->has('ppdb_ids') && is_array($request->ppdb_ids)) {
            $allSppIds = array_merge($allSppIds, $request->ppdb_ids);
        }

        // Ambil DU IDs yang dipilih
        $duIds = $request->has('du_ids') && is_array($request->du_ids) ? $request->du_ids : [];

        $selectedSpps = Spp::whereIn('spp_id', $allSppIds)
            ->whereIn('jenis', ['spp', 'ppdb'])
            ->select(['spp_id', 'nama', 'nominal', 'tahun_ajaran', 'jenis'])
            ->get();

        $selectedDus = Spp::whereIn('spp_id', $duIds)
            ->where('jenis', 'du')
            ->select(['spp_id', 'nama', 'nominal', 'tahun_ajaran', 'jenis', 'max_angsuran'])
            ->get();
        
        if ($selectedSpps->isEmpty() && $selectedDus->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada item pembayaran yang dipilih.');
        }
        
        $tahunAjaran = $selectedSpps->isNotEmpty() 
            ? $selectedSpps->first()->tahun_ajaran 
            : $selectedDus->first()->tahun_ajaran;

        $nominalDuPerAngsuran = 0;
        foreach ($selectedDus as $du) {
            $angsuranLunas = \App\Models\AngsuranDu::where('siswa_id', $siswa->siswa_id)
                ->where('spp_id', $du->spp_id)
                ->where('status', 'lunas')
                ->count();
            $nominalDuPerAngsuran += $du->max_angsuran > 0 ? round($du->nominal / $du->max_angsuran) : $du->nominal;
        }
        
        $totalTagihan = $selectedSpps->sum('nominal') + $nominalDuPerAngsuran;
        
        $buktiFileName = null;
        if ($request->hasFile('bukti_bayar')) {
            try {
                $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
                $buktiFileName = 'storage/' . $path;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error uploading file: ' . $e->getMessage())->withInput();
            }
        }
        
        DB::beginTransaction();
        
        try {
            $pembayaran = Pembayaran::create([
                'siswa_id' => $siswa->siswa_id,
                'admin_id' => 1,
                'tahun_ajaran' => $tahunAjaran,
                'total_tagihan' => $totalTagihan,
                'total_bayar' => $request->total_bayar,
                'bukti_bayar' => $buktiFileName,
                'keterangan' => $request->keterangan,
                'status_pembayaran' => 'pending',
                'tanggal_bayar' => now(),
                'is_aktif' => true
            ]);
            
            $detailsData = [];
            foreach ($selectedSpps as $spp) {
                $detailsData[] = [
                    'pembayaran_id' => $pembayaran->pembayaran_id,
                    'spp_id' => $spp->spp_id,
                    'siswa_id' => $siswa->siswa_id,
                    'biaya' => $spp->nominal,
                    'jumlah_bayar' => $spp->nominal,
                    'status_pembayaran' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            PembayaranDetail::insert($detailsData);

            // Simpan angsuran DU
            foreach ($selectedDus as $du) {
                $angsuranLunas = \App\Models\AngsuranDu::where('siswa_id', $siswa->siswa_id)
                    ->where('spp_id', $du->spp_id)
                    ->where('status', 'lunas')
                    ->count();
                $angsuranKe = $angsuranLunas + 1;
                $nominalPerAngsuran = $du->max_angsuran > 0 ? round($du->nominal / $du->max_angsuran) : $du->nominal;

                \App\Models\AngsuranDu::create([
                    'pembayaran_id'    => $pembayaran->pembayaran_id,
                    'siswa_id'         => $siswa->siswa_id,
                    'spp_id'           => $du->spp_id,
                    'angsuran_ke'      => $angsuranKe,
                    'nominal_angsuran' => $nominalPerAngsuran,
                    'jumlah_bayar'     => $nominalPerAngsuran,
                    'status'           => 'pending',
                    'tanggal_bayar'    => now(),
                    'bukti_bayar'      => $buktiFileName,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('pembayaran.history')
                ->with('success', 'Pembayaran berhasil dibuat dan menunggu konfirmasi admin.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($buktiFileName) {
                Storage::delete($buktiFileName);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function history()
    {
        $siswa = Auth::user()->siswa;

        // Jika data siswa tidak ditemukan, redirect dengan pesan error
        if (!$siswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Data siswa tidak ditemukan. Hubungi admin.');
        }
        
        $pembayaran = Pembayaran::with([
                'pembayaranDetail' => function($query) {
                    $query->with(['spp' => function($query) {
                        $query->select('spp_id', 'nama', 'nominal');
                    }]);
                },
                'angsuranDu' => function($query) {
                    $query->with(['spp' => function($query) {
                        $query->select('spp_id', 'nama', 'jenis');
                    }]);
                }
            ])
            ->select(['pembayaran_id', 'siswa_id', 'total_bayar', 'total_tagihan', 'status_pembayaran', 'tanggal_bayar', 'created_at', 'tahun_ajaran', 'keterangan', 'bukti_bayar'])
            ->where('siswa_id', $siswa->siswa_id)
            ->latest('created_at')
            ->paginate(10);
            
        $notificationsQuery = Pembayaran::with([
                'pembayaranDetail' => function($query) {
                    $query->with(['spp' => function($query) {
                        $query->select('spp_id', 'nama');
                    }]);
                },
                'angsuranDu' => function($query) {
                    $query->with(['spp' => function($query) {
                        $query->select('spp_id', 'nama', 'jenis');
                    }]);
                }
            ])
            ->select(['pembayaran_id', 'siswa_id', 'total_bayar', 'status_pembayaran', 'updated_at'])
            ->where('siswa_id', $siswa->siswa_id)
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
            } elseif ($payment->angsuranDu->isNotEmpty()) {
                $duName = optional($payment->angsuranDu->first()->spp)->nama;
                $itemsText = $duName ?: 'DU - ' . $payment->tahun_ajaran;
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
            
        return view('user.pembayaran.history', compact('pembayaran', 'notifications', 'unreadCount'));
    }

    public function getDetail($id)
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return response()->json(['error' => 'Data siswa tidak ditemukan'], 403);
        }
        
        $pembayaran = Pembayaran::with([
                'pembayaranDetail' => function($query) {
                    $query->with(['spp' => function($query) {
                        $query->select('spp_id', 'nama', 'nominal', 'jenis');
                    }]);
                },
                'angsuranDu' => function($query) {
                    $query->with(['spp' => function($query) {
                        $query->select('spp_id', 'nama', 'jenis');
                    }]);
                }
            ])
            ->select([
                'pembayaran_id', 'siswa_id', 'total_bayar', 'total_tagihan', 
                'bukti_bayar', 'keterangan', 'status_pembayaran', 'tanggal_bayar', 'tahun_ajaran'
            ])
            ->where('pembayaran_id', $id)
            ->where('siswa_id', $siswa->siswa_id)
            ->firstOrFail();
            
        $responseData = [
            'pembayaran_id' => $pembayaran->pembayaran_id,
            'siswa_id' => $pembayaran->siswa_id,
            'tahun_ajaran' => $pembayaran->tahun_ajaran,
            'total_tagihan' => $pembayaran->total_tagihan,
            'total_bayar' => $pembayaran->total_bayar,
            'bukti_bayar' => $pembayaran->bukti_bayar,
            'keterangan' => $pembayaran->keterangan,
            'status_pembayaran' => $pembayaran->status_pembayaran,
            'tanggal_bayar' => $pembayaran->tanggal_bayar,
            'pembayaranDetail' => $pembayaran->pembayaranDetail,
            'angsuranDu' => $pembayaran->angsuranDu
        ];
        
        return response()->json($responseData);
    }
}