<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AngsuranDu;
use App\Models\Spp;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AngsuranDuController extends Controller
{
    /**
     * Tampilkan semua angsuran DU milik siswa
     */
    public function index()
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Ambil semua tagihan DU yang aktif
        $duList = Spp::where('jenis', 'du')
            ->where('is_aktif', true)
            ->get();

        // Untuk setiap DU, ambil status angsuran siswa
        $angsuranList = [];
        foreach ($duList as $du) {
            $angsuranSiswa = AngsuranDu::where('siswa_id', $siswa->siswa_id)
                ->where('spp_id', $du->spp_id)
                ->orderBy('angsuran_ke')
                ->get();

            $totalLunas = $angsuranSiswa->where('status', 'lunas')->sum('jumlah_bayar');
            $angsuranList[] = [
                'du'           => $du,
                'angsuran'     => $angsuranSiswa,
                'total_lunas'  => $totalLunas,
                'sisa'         => $du->nominal - $totalLunas,
                'sudah_bayar'  => $angsuranSiswa->count(),
                'max'          => $du->max_angsuran,
            ];
        }

        return view('user.angsuran_du.index', compact('angsuranList'));
    }

    /**
     * Form bayar angsuran
     */
    public function create($spp_id)
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $du = Spp::where('spp_id', $spp_id)
            ->where('jenis', 'du')
            ->where('is_aktif', true)
            ->firstOrFail();

        // Cek angsuran yang sudah dibayar
        $angsuranSudahBayar = AngsuranDu::where('siswa_id', $siswa->siswa_id)
            ->where('spp_id', $spp_id)
            ->whereIn('status', ['lunas', 'pending'])
            ->count();

        // Cek apakah sudah maksimal angsuran
        if ($angsuranSudahBayar >= $du->max_angsuran) {
            return redirect()->route('siswa.angsuran.du.index')
                ->with('error', 'Angsuran DU sudah mencapai batas maksimal atau sedang dalam proses verifikasi.');
        }

        $angsuranKe       = $angsuranSudahBayar + 1;
        $nominalPerAngsuran = $du->nominal / $du->max_angsuran;

        return view('user.angsuran_du.create', compact('du', 'angsuranKe', 'nominalPerAngsuran', 'siswa'));
    }

    /**
     * Simpan pembayaran angsuran
     */
    public function store(Request $request, $spp_id)
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $du = Spp::where('spp_id', $spp_id)
            ->where('jenis', 'du')
            ->where('is_aktif', true)
            ->firstOrFail();

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'bukti_bayar'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $angsuranSudahBayar = AngsuranDu::where('siswa_id', $siswa->siswa_id)
            ->where('spp_id', $spp_id)
            ->whereIn('status', ['lunas', 'pending'])
            ->count();

        if ($angsuranSudahBayar >= $du->max_angsuran) {
            return redirect()->route('siswa.angsuran.du.index')
                ->with('error', 'Angsuran sudah mencapai batas maksimal.');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $path      = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $buktiPath = $path;
        }

        $nominalPerAngsuran = $du->nominal / $du->max_angsuran;

        DB::beginTransaction();
        try {
            // Buat record pembayaran induk jika belum ada
            $pembayaran = Pembayaran::create([
                'siswa_id'          => $siswa->siswa_id,
                'admin_id'          => 1,
                'tahun_ajaran'      => $du->tahun_ajaran,
                'total_tagihan'     => $nominalPerAngsuran,
                'total_bayar'       => $request->jumlah_bayar,
                'bukti_bayar'       => $buktiPath,
                'keterangan'        => 'Angsuran DU ke-' . ($angsuranSudahBayar + 1) . ' dari ' . $du->max_angsuran,
                'status_pembayaran' => 'pending',
                'tanggal_bayar'     => now(),
                'is_aktif'          => true,
            ]);

            // Buat record angsuran
            AngsuranDu::create([
                'pembayaran_id'     => $pembayaran->pembayaran_id,
                'siswa_id'          => $siswa->siswa_id,
                'spp_id'            => $du->spp_id,
                'angsuran_ke'       => $angsuranSudahBayar + 1,
                'nominal_angsuran'  => $nominalPerAngsuran,
                'jumlah_bayar'      => $request->jumlah_bayar,
                'status'            => 'pending',
                'tanggal_bayar'     => now(),
                'bukti_bayar'       => $buktiPath,
            ]);

            DB::commit();
            return redirect()->route('siswa.angsuran.du.index')
                ->with('success', 'Pembayaran angsuran DU ke-' . ($angsuranSudahBayar + 1) . ' berhasil dikirim. Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Admin: list semua angsuran DU
     */
    public function adminIndex(Request $request)
    {
        $query = AngsuranDu::with(['siswa', 'spp', 'pembayaran'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $angsuranList = $query->paginate(15);
        return view('admin.angsuran_du.index', compact('angsuranList'));
    }

    /**
     * Admin: verifikasi angsuran DU
     */
    public function verify(Request $request, $angsuran_id)
    {
        $request->validate([
            'status'     => 'required|in:lunas,ditolak',
            'keterangan' => 'nullable|string',
        ]);

        $angsuran = AngsuranDu::findOrFail($angsuran_id);

        DB::beginTransaction();
        try {
            $angsuran->update(['status' => $request->status]);

            // Update status pembayaran induk juga
            if ($angsuran->pembayaran) {
                $angsuran->pembayaran->update([
                    'status_pembayaran' => $request->status === 'lunas' ? 'lunas' : 'ditolak',
                    'keterangan'        => $request->keterangan,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Status angsuran berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
