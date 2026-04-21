<?php

namespace Database\Seeders;

use App\Models\Spp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        foreach ($bulan as $nama) {
            Spp::create([
                'nama' => 'SPP - ' . $nama,
                'tahun_ajaran' => '2025/2026',
                'nominal' => 200000,
                'is_aktif' => true
            ]);
        }
    }
}
