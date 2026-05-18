<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spp;
use App\Models\Siswa;
use App\Models\Admin;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SppController extends Controller
{
    public function index(Request $request)
    {
        $query = Spp::select(['spp_id', 'nama', 'jenis', 'tahun_ajaran', 'nominal', 'max_angsuran', 'is_aktif', 'created_at']);
        
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $spp = $query->latest()->paginate(10);

        $counts = DB::table('spp')
            ->select(DB::raw("
                SUM(CASE WHEN jenis = 'spp' THEN 1 ELSE 0 END) as spp_count,
                SUM(CASE WHEN jenis = 'ppdb' THEN 1 ELSE 0 END) as ppdb_count,
                SUM(CASE WHEN jenis = 'du' THEN 1 ELSE 0 END) as du_count
            "))
            ->first();
            
        $sppCount = $counts->spp_count ?? 0;
        $ppdbCount = $counts->ppdb_count ?? 0;
        $duCount = $counts->du_count ?? 0;

        return view('admin.spp.index', compact('spp', 'sppCount', 'ppdbCount', 'duCount'));
    }

    public function create()
    {
        return view('admin.spp.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nama'        => 'required|string|max:255',
            'jenis'       => 'required|in:spp,ppdb,du',
            'tahun_ajaran'=> 'required',
            'nominal'     => 'required|numeric|min:0',
            'is_aktif'    => 'boolean',
        ];

        if ($request->jenis === 'du') {
            $rules['max_angsuran'] = 'required|integer|min:1|max:24';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            Spp::create([
                'nama'          => $request->nama,
                'jenis'         => $request->jenis,
                'tahun_ajaran'  => $request->tahun_ajaran,
                'nominal'       => $request->nominal,
                'max_angsuran'  => $request->jenis === 'du' ? $request->max_angsuran : null,
                'is_aktif'      => $request->is_aktif ?? true,
            ]);
            
            DB::commit();
            return redirect()->route('admin.spp.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Spp $spp)
    {
        return view('admin.spp.edit', compact('spp'));
    }

    public function update(Request $request, Spp $spp)
    {
        $rules = [
            'nama'        => 'required|string|max:255',
            'jenis'       => 'required|in:spp,ppdb,du',
            'tahun_ajaran'=> 'required',
            'nominal'     => 'required|numeric|min:0',
            'is_aktif'    => 'boolean',
        ];

        if ($request->jenis === 'du') {
            $rules['max_angsuran'] = 'required|integer|min:1|max:24';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $spp->update([
                'nama'         => $request->nama,
                'jenis'        => $request->jenis,
                'tahun_ajaran' => $request->tahun_ajaran,
                'nominal'      => $request->nominal,
                'max_angsuran' => $request->jenis === 'du' ? $request->max_angsuran : null,
                'is_aktif'     => $request->is_aktif ?? $spp->is_aktif,
            ]);
            
            DB::commit();
            return redirect()->route('admin.spp.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Spp $spp)
    {
        $isUsed = DB::table('pembayaran_detail')
            ->where('spp_id', $spp->spp_id)
            ->exists();

        $isUsedDu = DB::table('angsuran_du')
            ->where('spp_id', $spp->spp_id)
            ->exists();
            
        if ($isUsed || $isUsedDu) {
            return redirect()->route('admin.spp.index')
                ->with('error', 'Data tidak dapat dihapus karena sudah digunakan dalam pembayaran');
        }
        
        DB::beginTransaction();
        try {
            $spp->delete();
            DB::commit();
            return redirect()->route('admin.spp.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
