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
        $query = Spp::select(['spp_id', 'nama', 'tahun_ajaran', 'nominal', 'is_aktif', 'created_at']);
        
        if ($request->filled('jenis')) {
            $prefix = $request->jenis === 'spp' ? 'SPP -' : 'PPDB -';
            $query->where('nama', 'like', $prefix . '%');
        }

        $spp = $query->latest()->paginate(10);

        $counts = DB::table('spp')
            ->select(DB::raw("
                SUM(CASE WHEN nama LIKE 'SPP -%' THEN 1 ELSE 0 END) as spp_count,
                SUM(CASE WHEN nama LIKE 'PPDB -%' THEN 1 ELSE 0 END) as ppdb_count
            "))
            ->first();
            
        $sppCount = $counts->spp_count ?? 0;
        $ppdbCount = $counts->ppdb_count ?? 0;

        return view('admin.spp.index', compact('spp', 'sppCount', 'ppdbCount'));
    }

    public function create()
    {
        return view('admin.spp.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required',
            'nominal' => 'required|numeric',
            'is_aktif' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            Spp::create([
                'nama' => $request->nama,
                'tahun_ajaran' => $request->tahun_ajaran,
                'nominal' => $request->nominal,
                'is_aktif' => $request->is_aktif ?? true
            ]);
            
            DB::commit();
            return redirect()->route('admin.spp.index')->with('success', 'Data SPP berhasil ditambahkan');
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required',
            'nominal' => 'required|numeric',
            'is_aktif' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $spp->update([
                'nama' => $request->nama,
                'tahun_ajaran' => $request->tahun_ajaran,
                'nominal' => $request->nominal,
                'is_aktif' => $request->is_aktif ?? $spp->is_aktif
            ]);
            
            DB::commit();
            return redirect()->route('admin.spp.index')->with('success', 'Data SPP berhasil diperbarui');
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
            
        if ($isUsed) {
            return redirect()->route('admin.spp.index')
                ->with('error', 'Data SPP tidak dapat dihapus karena sudah digunakan dalam pembayaran');
        }
        
        DB::beginTransaction();
        try {
            $spp->delete();
            
            DB::commit();
            return redirect()->route('admin.spp.index')->with('success', 'Data SPP berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
