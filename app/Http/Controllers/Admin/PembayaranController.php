<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with([
            'siswa:siswa_id,nama,nis,kelas', 
            'pembayaranDetail' => function($query) {
                $query->with(['spp:spp_id,nama,nominal']);
            }, 
            'admin:admin_id,nama'
        ]);
        
        // Handle the special case for unpaid current month SPP
        if ($request->filled('bulan') && $request->filled('tahun') && $request->status === 'belum_bayar') {
            // Get current month name
            $months = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ];
            
            $monthName = $months[$request->bulan];
            $year = $request->tahun;
            
            // Find the SPP item for this month
            $currentMonthSpp = Spp::where('nama', 'like', "%SPP%")
                                ->where('nama', 'like', "%$monthName%")
                                ->where('is_aktif', true)
                                ->first();
            
            if ($currentMonthSpp) {
                // For monitoring - log the SPP we found
                \Log::info("Found SPP for {$monthName}: " . $currentMonthSpp->nama . " (ID: {$currentMonthSpp->spp_id})");
                
                // Get all students who should have paid for this SPP
                $allStudents = Siswa::where('is_aktif', true)->get();
                \Log::info("Total active students: " . $allStudents->count());
                
                // Get students who have already paid for this SPP
                $paidStudentIds = PembayaranDetail::where('spp_id', $currentMonthSpp->spp_id)
                    ->where('status_pembayaran', 'lunas')
                    ->pluck('siswa_id')
                    ->toArray();
                \Log::info("Students who paid: " . count($paidStudentIds));
                
                // Get the students who haven't paid
                $unpaidStudentIds = $allStudents->pluck('siswa_id')->diff($paidStudentIds)->toArray();
                \Log::info("Students with unpaid SPP: " . count($unpaidStudentIds));
                
                // Filter to show only pembayaran for students who haven't paid
                if (!empty($unpaidStudentIds)) {
                    $query->whereIn('siswa_id', $unpaidStudentIds);
                    
                    // We also need to ensure we're looking at payments that aren't 'lunas'
                    $query->where(function($q) {
                        $q->where('status_pembayaran', 'pending')
                          ->orWhere('status_pembayaran', 'ditolak');
                    });
                } else {
                    // No unpaid students - return empty result
                    $pembayaran = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
                    return view('admin.pembayaran.index', compact('pembayaran'))->with('message', 'Semua siswa sudah lunas untuk SPP ' . $monthName);
                }
            } else {
                // No SPP found for this month
                \Log::warning("No SPP found for month: $monthName");
                $pembayaran = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
                return view('admin.pembayaran.index', compact('pembayaran'))->with('message', 'Tidak ada SPP untuk bulan ' . $monthName);
            }
        } else {
            // Regular filtering by status
            if ($request->filled('status')) {
                $query->where('status_pembayaran', $request->status);
            }
        }
        
        $pembayaran = $query->select([
                'pembayaran_id', 'siswa_id', 'admin_id', 'total_bayar', 
                'total_tagihan', 'status_pembayaran', 'tanggal_bayar', 
                'created_at', 'tahun_ajaran'
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();
            
        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $siswa = Siswa::select(['siswa_id', 'nama', 'nis', 'kelas'])
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();
            
        $kelas = Siswa::select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');
            
        $spp = Spp::select(['spp_id', 'nama', 'nominal', 'tahun_ajaran'])
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        return view('admin.pembayaran.create', compact('siswa', 'spp', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,siswa_id',
            'payment_items' => 'required|array',
            'payment_items.*.spp_id' => 'required|exists:spp,spp_id',
            'total_tagihan' => 'required|numeric|min:0',
            'total_bayar' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum_bayar,pending,lunas,ditolak',
            'tanggal_bayar' => 'required|date',
            'tahun_ajaran' => 'required|string',
            'metode_pembayaran' => 'required|string',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $admin = Admin::select('admin_id')
            ->where('user_id', Auth::id())
            ->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan');
        }

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
                'admin_id' => $admin->admin_id,
                'siswa_id' => $request->siswa_id,
                'tahun_ajaran' => $request->tahun_ajaran,
                'total_tagihan' => $request->total_tagihan,
                'total_bayar' => $request->total_bayar,
                'bukti_bayar' => $buktiFileName,
                'keterangan' => $request->keterangan ?? 'Pembayaran langsung ke bendahara sekolah',
                'status_pembayaran' => $request->status_pembayaran,
                'tanggal_bayar' => $request->tanggal_bayar,
                'is_aktif' => true
            ]);
            
            $sppIds = collect($request->payment_items)->pluck('spp_id')->toArray();
            $sppData = Spp::whereIn('spp_id', $sppIds)
                ->select(['spp_id', 'nominal'])
                ->get()
                ->keyBy('spp_id');
            
            $detailsData = [];
            foreach ($request->payment_items as $item) {
                $spp = $sppData[$item['spp_id']] ?? null;
                if ($spp) {
                    $detailsData[] = [
                        'pembayaran_id' => $pembayaran->pembayaran_id,
                        'spp_id' => $spp->spp_id,
                        'siswa_id' => $request->siswa_id,
                        'biaya' => $spp->nominal,
                        'jumlah_bayar' => $spp->nominal,
                        'status_pembayaran' => $request->status_pembayaran,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            if (!empty($detailsData)) {
                PembayaranDetail::insert($detailsData);
            }
            
            DB::commit();
            
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($buktiFileName) {
                Storage::delete(str_replace('storage/', 'public/', $buktiFileName));
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load([
            'siswa:siswa_id,nama,nis,kelas',
            'pembayaranDetail' => function($query) {
                $query->with(['spp:spp_id,nama,nominal']);
            },
            'admin:admin_id,nama'
        ]);
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }
    
    public function getItems($id)
    {
        $pembayaran = Pembayaran::with(['pembayaranDetail' => function($query) {
                $query->with(['spp:spp_id,nama,nominal']);
            }])
            ->select(['pembayaran_id'])
            ->findOrFail($id);
            
        return response()->json([
            'items' => $pembayaran->pembayaranDetail
        ]);
    }

    public function edit(Pembayaran $pembayaran)
    {
        $pembayaran->load([
            'siswa:siswa_id,nama,nis,kelas',
            'pembayaranDetail' => function($query) {
                $query->with(['spp:spp_id,nama,nominal']);
            }
        ]);
        
        $siswa = Siswa::select(['siswa_id', 'nama', 'nis', 'kelas'])
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();
            
        $spp = Spp::select(['spp_id', 'nama', 'nominal', 'tahun_ajaran'])
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();
            
        return view('admin.pembayaran.edit', compact('pembayaran', 'siswa', 'spp'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,siswa_id',
            'spp_id' => 'required|exists:spp,spp_id',
            'total_bayar' => 'required|numeric',
            'status_pembayaran' => 'required|in:pending,lunas,ditolak,belum_bayar',
            'tanggal_bayar' => 'required|date',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $spp = Spp::select(['spp_id', 'nominal'])
            ->findOrFail($request->spp_id);
            
        $total_tagihan = $spp->nominal;
        
        $pembayaran->siswa_id = $request->siswa_id;
        $pembayaran->total_tagihan = $total_tagihan;
        $pembayaran->total_bayar = $request->total_bayar;
        $pembayaran->status_pembayaran = $request->status_pembayaran;
        $pembayaran->tanggal_bayar = $request->tanggal_bayar;

        if ($request->hasFile('bukti_bayar') && $request->file('bukti_bayar')->isValid()) {
            try {
                if ($pembayaran->bukti_bayar) {
                    $oldPath = str_replace('storage/', '', $pembayaran->bukti_bayar);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
                $pembayaran->bukti_bayar = 'storage/' . $path;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error uploading file: ' . $e->getMessage())->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $pembayaran->save();
            
            PembayaranDetail::updateOrCreate(
                ['pembayaran_id' => $pembayaran->pembayaran_id],
                [
                    'siswa_id' => $request->siswa_id,
                    'spp_id' => $request->spp_id,
                    'biaya' => $total_tagihan,
                    'jumlah_bayar' => $request->total_bayar,
                    'status_pembayaran' => $request->status_pembayaran
                ]
            );
            
            DB::commit();
            
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Pembayaran $pembayaran)
    {
        DB::beginTransaction();
        try {
            PembayaranDetail::where('pembayaran_id', $pembayaran->pembayaran_id)->delete();
            
            $pembayaran->delete();
            
            if ($pembayaran->bukti_bayar) {
                $path = str_replace('storage/', 'public/', $pembayaran->bukti_bayar);
                Storage::delete($path);
            }
            
            DB::commit();
            
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,lunas,ditolak,belum_bayar',
            'keterangan' => 'nullable|string'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $pembayaran->status_pembayaran = $request->status_pembayaran;
            if ($request->filled('keterangan')) {
                $pembayaran->keterangan = $request->keterangan;
            }
            $pembayaran->save();
            
            if ($request->status_pembayaran === 'ditolak') {
                // Hapus pembayaran_detail agar tagihan kembali muncul ke user
                PembayaranDetail::where('pembayaran_id', $id)->delete();
            } else {
                PembayaranDetail::where('pembayaran_id', $id)
                    ->update(['status_pembayaran' => $request->status_pembayaran]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diperbarui',
                'redirect' => route('admin.pembayaran.index')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSiswaByKelas($kelas)
    {
        $siswa = Siswa::select(['siswa_id', 'nama', 'nis'])
            ->where('kelas', $kelas)
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();
            
        return response()->json($siswa);
    }

    public function getAllSiswa()
    {
        $siswa = Siswa::select(['siswa_id', 'nama', 'nis', 'kelas'])
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();
            
        return response()->json($siswa);
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'required|exists:siswa,siswa_id',
            'spp_id' => 'required|exists:spp,spp_id',
            'total_bayar' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum_bayar,pending,lunas,ditolak',
            'tanggal_bayar' => 'required|date',
            'tahun_ajaran' => 'required|string',
        ]);

        $admin = Admin::select('admin_id')
            ->where('user_id', Auth::id())
            ->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin tidak ditemukan');
        }

        $spp = Spp::select(['spp_id', 'nominal', 'tahun_ajaran'])
            ->findOrFail($request->spp_id);

        DB::beginTransaction();
        try {
            $pembayaranData = [];
            $now = now();
            
            foreach ($request->siswa_ids as $siswaId) {
                $pembayaranData[] = [
                    'admin_id' => $admin->admin_id,
                    'siswa_id' => $siswaId,
                    'tahun_ajaran' => $spp->tahun_ajaran,
                    'total_tagihan' => $spp->nominal,
                    'total_bayar' => $request->total_bayar,
                    'keterangan' => $request->keterangan ?? 'Pembayaran batch oleh admin',
                    'status_pembayaran' => $request->status_pembayaran,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'is_aktif' => true,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            
            $pembayaranIds = [];
            if (!empty($pembayaranData)) {
                foreach ($pembayaranData as $data) {
                    $pembayaran = Pembayaran::create($data);
                    $pembayaranIds[] = $pembayaran->pembayaran_id;
                }
                
                $detailsData = [];
                foreach ($pembayaranIds as $index => $pembayaranId) {
                    $detailsData[] = [
                        'pembayaran_id' => $pembayaranId,
                        'spp_id' => $spp->spp_id,
                        'siswa_id' => $request->siswa_ids[$index],
                        'biaya' => $spp->nominal,
                        'jumlah_bayar' => $request->total_bayar,
                        'status_pembayaran' => $request->status_pembayaran,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }
                
                if (!empty($detailsData)) {
                    PembayaranDetail::insert($detailsData);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran batch berhasil ditambahkan untuk ' . count($request->siswa_ids) . ' siswa');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}