<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Setting;
use App\Models\User;
use App\Services\KenaikanKelasService;
use App\Imports\SiswaImport;
use App\Exports\TemplateExportSiswa;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelas = $request->kelas;
        
        $query = Siswa::with(['user' => function($q) {
                $q->select('user_id', 'email', 'role');
            }])
            ->select(['siswa_id', 'user_id', 'nama', 'nis', 'kelas', 'tanggal_masuk', 'is_aktif'])
            ->where('is_aktif', true);

        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        if (!$kelas || empty($kelas)) {
            $siswa = $query->orderBy('kelas')
                        ->orderBy('nama')
                        ->get()
                        ->groupBy('kelas')
                        ->sortKeysUsing(function($a, $b) {
                            $romanOrder = ['X RPL 1' => 1, 'X RPL 2' => 2, 'X TAV' => 3, 'X TMI 1' => 4, 'X TMI 2' => 5, 'X TKR 1' => 6, 'X TKR 2' => 7, 'X TKR 3' => 8, 'X TKR 4' => 9, 'X TKR 5' => 10, 'XI RPL' => 11, 'XI TAV' => 12, 'XI TMI 1' => 13, 'XI TMI 2' => 14, 'XI TKR 1' => 15, 'XI TKR 2' => 16, 'XI TKR 3' => 17, 'XI TKR 4' => 18, 'XI TKR 5' => 19, 'XII RPL' => 20, 'XII TAV' => 21, 'XII TMI 1' => 22, 'XII TMI 2' => 23, 'XII TKR 1' => 24, 'XII TKR 2' => 25, 'XII TKR 3' => 26, 'XII TKR 4' => 27, 'XII TKR 5' => 28];
                            return ($romanOrder[$a] ?? 999) <=> ($romanOrder[$b] ?? 999);
                        });
        } else {
            $siswa = $query->orderBy('nama')->get();
        }

        return view('admin.siswa.index', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|numeric|unique:siswa,nis',
            'kelas' => 'required|string|in:X RPL 1,X RPL 2,X TAV,X TMI 1,X TMI 2,X TKR 1,X TKR 2,X TKR 3,X TKR 4,X TKR 5,XI RPL,XI TAV,XI TMI 1,XI TMI 2,XI TKR 1,XI TKR 2,XI TKR 3,XI TKR 4,XI TKR 5,XII RPL,XII TAV,XII TMI 1,XII TMI 2,XII TKR 1,XII TKR 2,XII TKR 3,XII TKR 4,XII TKR 5',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:3|confirmed',
            'tanggal_masuk' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->user_id,
                'nama' => $request->nama,
                'nis' => $request->nis,
                'kelas' => $request->kelas,
                'tanggal_masuk' => $request->tanggal_masuk,
                'is_aktif' => true
            ]);

            DB::commit();
            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(string $id)
    {
        $siswa = Siswa::with('user:user_id,email')
            ->select(['siswa_id', 'user_id', 'nama', 'nis', 'kelas', 'tanggal_masuk', 'tidak_naik_kelas'])
            ->findOrFail($id);

        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|numeric|unique:siswa,nis,' . $siswa->siswa_id . ',siswa_id',
            'kelas' => 'required|string|in:X RPL 1,X RPL 2,X TAV,X TMI 1,X TMI 2,X TKR 1,X TKR 2,X TKR 3,X TKR 4,X TKR 5,XI RPL,XI TAV,XI TMI 1,XI TMI 2,XI TKR 1,XI TKR 2,XI TKR 3,XI TKR 4,XI TKR 5,XII RPL,XII TAV,XII TMI 1,XII TMI 2,XII TKR 1,XII TKR 2,XII TKR 3,XII TKR 4,XII TKR 5',
            'tanggal_masuk' => 'required|date'
        ]);

        $siswa->update([
            'nama'             => $request->nama,
            'nis'              => $request->nis,
            'kelas'            => $request->kelas,
            'tanggal_masuk'    => $request->tanggal_masuk,
            'tidak_naik_kelas' => $request->boolean('tidak_naik_kelas'),
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $siswa = Siswa::findOrFail($id);
            $user = User::findOrFail($siswa->user_id);

            $siswa->delete();
            $user->delete();

            DB::commit();
            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportPDF(Request $request)
    {
        $kelas = $request->kelas;
        
        $query = Siswa::with('user:user_id,email')
            ->select(['siswa_id', 'user_id', 'nama', 'nis', 'kelas', 'tanggal_masuk'])
            ->where('is_aktif', true);
            
        if ($kelas) {
            $query->where('kelas', $kelas);
        }
        
        $siswa = $query->orderBy('kelas')
            ->orderBy('nama')
            ->get();

        $pdf = Pdf::loadView('admin.siswa.pdf', compact('siswa', 'kelas'));
        $filename = 'daftar-siswa' . ($kelas ? '-kelas-' . $kelas : '') . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function migrateClass(Request $request)
    {
        $request->validate([
            'from_kelas' => 'required|string|in:X RPL 1,X RPL 2,X TAV,X TMI 1,X TMI 2,X TKR 1,X TKR 2,X TKR 3,X TKR 4,X TKR 5,XI RPL,XI TAV,XI TMI 1,XI TMI 2,XI TKR 1,XI TKR 2,XI TKR 3,XI TKR 4,XI TKR 5,XII RPL,XII TAV,XII TMI 1,XII TMI 2,XII TKR 1,XII TKR 2,XII TKR 3,XII TKR 4,XII TKR 5',
            'to_kelas' => 'required|string|in:X RPL 1,X RPL 2,X TAV,X TMI 1,X TMI 2,X TKR 1,X TKR 2,X TKR 3,X TKR 4,X TKR 5,XI RPL,XI TAV,XI TMI 1,XI TMI 2,XI TKR 1,XI TKR 2,XI TKR 3,XI TKR 4,XI TKR 5,XII RPL,XII TAV,XII TMI 1,XII TMI 2,XII TKR 1,XII TKR 2,XII TKR 3,XII TKR 4,XII TKR 5,LULUS',
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'required|exists:siswa,siswa_id'
        ]);

        DB::beginTransaction();
        try {
            if ($request->to_kelas === 'LULUS') {
                Siswa::whereIn('siswa_id', $request->siswa_ids)
                    ->update(['is_aktif' => false]);
            } else {
                Siswa::whereIn('siswa_id', $request->siswa_ids)
                    ->update(['kelas' => $request->to_kelas]);
            }

            DB::commit();
            return redirect()->route('admin.siswa.index')
                ->with('success', count($request->siswa_ids) . ' siswa berhasil dipindahkan ke ' . ($request->to_kelas === 'LULUS' ? 'status lulus' : 'kelas ' . $request->to_kelas));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Toggle status tidak_naik_kelas via AJAX.
     */
    public function toggleTidakNaik(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update([
            'tidak_naik_kelas' => !$siswa->tidak_naik_kelas,
        ]);

        return response()->json([
            'success'          => true,
            'tidak_naik_kelas' => $siswa->tidak_naik_kelas,
            'nama'             => $siswa->nama,
        ]);
    }

    /**
     * Tampilkan halaman pengaturan kenaikan kelas.
     */
    public function pengaturanKenaikan()
    {
        $bulanKenaikan = (int) Setting::get('bulan_kenaikan_kelas', 7);
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April',   5 => 'Mei',       6 => 'Juni',
            7 => 'Juli',    8 => 'Agustus',   9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('admin.siswa.pengaturan_kenaikan', compact('bulanKenaikan', 'namaBulan'));
    }

    /**
     * Simpan pengaturan bulan kenaikan kelas.
     */
    public function simpanPengaturanKenaikan(Request $request)
    {
        $request->validate([
            'bulan_kenaikan_kelas' => 'required|integer|min:1|max:12',
        ]);

        Setting::set('bulan_kenaikan_kelas', $request->bulan_kenaikan_kelas);

        return redirect()->route('admin.siswa.pengaturan-kenaikan')
            ->with('success', 'Pengaturan bulan kenaikan kelas berhasil disimpan.');
    }

    /**
     * Proses kenaikan kelas secara manual dari admin.
     */
    public function prosesKenaikanManual(Request $request, KenaikanKelasService $service)
    {
        $request->validate([
            'tahun_ajaran' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
        ]);

        $result = $service->prosesKenaikanManual($request->tahun_ajaran);

        if ($result['success']) {
            return redirect()->route('admin.siswa.pengaturan-kenaikan')
                ->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Download template Excel untuk import siswa
     */
    public function downloadTemplate()
    {
        return Excel::download(new TemplateExportSiswa(), 'template-import-siswa.xlsx');
    }

    /**
     * Import siswa dari file Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file_excel.required' => 'File Excel wajib dipilih.',
            'file_excel.mimes'    => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file_excel.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $import = new SiswaImport();
            Excel::import($import, $request->file('file_excel'));

            $message = "Import selesai. Berhasil: {$import->success} siswa";
            if ($import->skipped > 0) {
                $message .= ", Dilewati: {$import->skipped} baris";
            }

            if (!empty($import->errors)) {
                return redirect()->route('admin.siswa.index')
                    ->with('import_success', $import->success)
                    ->with('import_errors', $import->errors)
                    ->with('warning', $message . ", Gagal: " . count($import->errors) . " baris. Lihat detail di bawah.");
            }

            return redirect()->route('admin.siswa.index')
                ->with('success', $message . '.');

        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')
                ->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}