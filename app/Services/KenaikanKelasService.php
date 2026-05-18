<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\KenaikanKelasLog;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KenaikanKelasService
{
    // Peta kenaikan kelas yang spesifik dan tepat
    const PETA_NAIK = [
        // Kelas X → XI
        'X RPL 1'  => 'XI RPL',
        'X RPL 2'  => 'XI RPL',
        'X TAV'    => 'XI TAV',
        'X TMI 1'  => 'XI TMI 1',
        'X TMI 2'  => 'XI TMI 2',
        'X TKR 1'  => 'XI TKR 1',
        'X TKR 2'  => 'XI TKR 2',
        'X TKR 3'  => 'XI TKR 3',
        'X TKR 4'  => 'XI TKR 4',
        'X TKR 5'  => 'XI TKR 5',
        // Kelas XI → XII
        'XI RPL'   => 'XII RPL',
        'XI TAV'   => 'XII TAV',
        'XI TMI 1' => 'XII TMI 1',
        'XI TMI 2' => 'XII TMI 2',
        'XI TKR 1' => 'XII TKR 1',
        'XI TKR 2' => 'XII TKR 2',
        'XI TKR 3' => 'XII TKR 3',
        'XI TKR 4' => 'XII TKR 4',
        'XI TKR 5' => 'XII TKR 5',
    ];

    /**
     * Dipanggil scheduler setiap hari.
     * Hanya berjalan jika bulan sekarang = bulan_kenaikan_kelas di settings.
     */
    public function prosesKenaikanOtomatis(): void
    {
        $sekarang        = Carbon::now();
        $bulanKenaikan   = (int) Setting::get('bulan_kenaikan_kelas', 7);
        $tahunAjaranBaru = $this->hitungTahunAjaranBaru($sekarang->year);

        if ($sekarang->month !== $bulanKenaikan) {
            return;
        }

        // Cegah double proses dalam satu tahun ajaran
        if (KenaikanKelasLog::where('tahun_ajaran', $tahunAjaranBaru)->exists()) {
            Log::info("[KenaikanKelas] Sudah diproses untuk {$tahunAjaranBaru}, dilewati.");
            return;
        }

        Log::info("[KenaikanKelas] Mulai — bulan {$bulanKenaikan}, tahun ajaran {$tahunAjaranBaru}");

        $siswaList = Siswa::where('is_aktif', true)
            ->where('sudah_dinaikkan', false)
            ->where('lulus', false)
            ->get();

        if ($siswaList->isEmpty()) {
            Log::info("[KenaikanKelas] Tidak ada siswa yang perlu diproses.");
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($siswaList as $siswa) {
                $this->prosesPerSiswa($siswa, $tahunAjaranBaru);
            }
            // Reset agar tahun ajaran berikutnya bisa diproses kembali
            Siswa::where('is_aktif', true)->update(['sudah_dinaikkan' => false]);

            DB::commit();
            Log::info("[KenaikanKelas] Selesai. Total: {$siswaList->count()} siswa.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[KenaikanKelas] Gagal: " . $e->getMessage());
        }
    }

    /**
     * Dipanggil langsung dari admin — tidak cek bulan.
     * Mengembalikan array ['success', 'message', 'total'].
     */
    public function prosesKenaikanManual(string $tahunAjaranBaru): array
    {
        if (KenaikanKelasLog::where('tahun_ajaran', $tahunAjaranBaru)->exists()) {
            return [
                'success' => false,
                'message' => "Tahun ajaran {$tahunAjaranBaru} sudah pernah diproses.",
            ];
        }

        $siswaList = Siswa::where('is_aktif', true)
            ->where('lulus', false)
            ->get();

        if ($siswaList->isEmpty()) {
            return ['success' => false, 'message' => 'Tidak ada siswa aktif yang perlu diproses.'];
        }

        DB::beginTransaction();
        try {
            foreach ($siswaList as $siswa) {
                $this->prosesPerSiswa($siswa, $tahunAjaranBaru);
            }
            Siswa::where('is_aktif', true)->update(['sudah_dinaikkan' => false]);

            DB::commit();
            return [
                'success' => true,
                'message' => "Kenaikan kelas berhasil untuk {$siswaList->count()} siswa.",
                'total'   => $siswaList->count(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    private function prosesPerSiswa(Siswa $siswa, string $tahunAjaranBaru): void
    {
        $kelasLama = $siswa->kelas;

        // Siswa ditandai tidak naik kelas oleh admin
        if ($siswa->tidak_naik_kelas) {
            KenaikanKelasLog::create([
                'siswa_id'     => $siswa->siswa_id,
                'nama_siswa'   => $siswa->nama,
                'kelas_lama'   => $kelasLama,
                'kelas_baru'   => $kelasLama,
                'tahun_ajaran' => $tahunAjaranBaru,
                'status'       => 'tidak_naik',
            ]);
            $siswa->update([
                'sudah_dinaikkan'  => true,
                'tahun_ajaran'     => $tahunAjaranBaru,
                'tidak_naik_kelas' => false, // reset untuk tahun berikutnya
            ]);
            return;
        }

        // Kelas XII → lulus
        if (str_starts_with($kelasLama, 'XII')) {
            KenaikanKelasLog::create([
                'siswa_id'     => $siswa->siswa_id,
                'nama_siswa'   => $siswa->nama,
                'kelas_lama'   => $kelasLama,
                'kelas_baru'   => 'LULUS',
                'tahun_ajaran' => $tahunAjaranBaru,
                'status'       => 'lulus',
            ]);
            $siswa->update([
                'lulus'           => true,
                'is_aktif'        => false,
                'sudah_dinaikkan' => true,
                'tahun_ajaran'    => $tahunAjaranBaru,
            ]);
            return;
        }

        // Kelas X → XI, XI → XII (exact match per kelas)
        $kelasBaru = self::PETA_NAIK[$kelasLama] ?? null;

        if (!$kelasBaru) {
            Log::warning("[KenaikanKelas] Format kelas tidak dikenali: {$kelasLama} (siswa_id: {$siswa->siswa_id})");
            return;
        }

        KenaikanKelasLog::create([
            'siswa_id'     => $siswa->siswa_id,
            'nama_siswa'   => $siswa->nama,
            'kelas_lama'   => $kelasLama,
            'kelas_baru'   => $kelasBaru,
            'tahun_ajaran' => $tahunAjaranBaru,
            'status'       => 'naik',
        ]);
        $siswa->update([
            'kelas'           => $kelasBaru,
            'sudah_dinaikkan' => true,
            'tahun_ajaran'    => $tahunAjaranBaru,
        ]);
    }

    private function hitungTahunAjaranBaru(int $tahun): string
    {
        return $tahun . '/' . ($tahun + 1);
    }
}