<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
            ->select(['siswa_id', 'user_id', 'nama', 'nis', 'kelas', 'tanggal_masuk'])
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
            'nama' => $request->nama,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'tanggal_masuk' => $request->tanggal_masuk
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
        return $pdf->download('daftar-siswa' . ($kelas ? '-kelas-' . $kelas : '') . '.pdf');
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
                // For graduating students, set is_aktif to false
                Siswa::whereIn('siswa_id', $request->siswa_ids)
                    ->update(['is_aktif' => false]);
            } else {
                // For regular class migration, update the class
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
}