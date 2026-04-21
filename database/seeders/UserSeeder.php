<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Siswa;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        $adminUser = User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Admin::create([
            'user_id' => $adminUser->user_id,
            'nama' => 'Bendahara',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Admin No. 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Kepsek user
        $kepsekUser = User::create([
            'email' => 'kepsek@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'kepsek',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Admin::create([
            'user_id' => $kepsekUser->user_id,
            'nama' => 'Kepala Sekolah',
            'no_telp' => '089876543210',
            'alamat' => 'Jl. Kepsek No. 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create 150 Siswa (25 siswa per kelas I-VI)
        $kelas = ['X RPL', 'X TAV', 'X TMI', 'X TKR', 'XI RPL', 'XI TAV', 'XI TMI', 'XI TKR', 'XII RPL', 'XII TAV', 'XII TMI', 'XII TKR'];
        
        // Nama-nama anak Indonesia (laki-laki dan perempuan)
        $namaDepan = [
            // Nama Laki-laki
            'Adi', 'Budi', 'Cahyo', 'Dimas', 'Eko', 'Fajar', 'Galih', 'Hadi', 
            'Irfan', 'Joko', 'Kevin', 'Lutfi', 'Muhammad', 'Nanda', 'Oki', 
            'Pandu', 'Rafi', 'Satria', 'Tegar', 'Umar', 'Vino', 'Wahyu', 
            'Yusuf', 'Zaki', 'Aditya', 'Bagas', 'Candra', 'Dewa', 'Erlangga',
            
            // Nama Perempuan
            'Amelia', 'Bunga', 'Citra', 'Dewi', 'Elsa', 'Farah', 'Gita', 
            'Hana', 'Indah', 'Jasmine', 'Kirana', 'Lestari', 'Mawar', 'Nadia', 
            'Olivia', 'Putri', 'Ratna', 'Sari', 'Tiara', 'Utari', 'Vina', 
            'Wulan', 'Yanti', 'Zahra', 'Annisa', 'Bella', 'Cindy', 'Dina', 'Eka'
        ];
        
        $namaBelakang = [
            'Wijaya', 'Saputra', 'Susanto', 'Gunawan', 'Santoso', 'Kusuma', 
            'Pratama', 'Nugraha', 'Hidayat', 'Wibowo', 'Putra', 'Sanjaya', 
            'Ramadhan', 'Permana', 'Nugroho', 'Firmansyah', 'Sulistyo', 
            'Setiawan', 'Kurniawan', 'Suryanto', 'Purnama', 'Hartono', 
            'Utama', 'Setiadi', 'Laksono', 'Prabowo', 'Mulya', 'Hermawan',
            'Sukarno', 'Harahap', 'Setiabudi', 'Siregar', 'Sihombing',
            'Hutagalung', 'Hutapea', 'Sihotang', 'Sitompul', 'Manurung'
        ];
        
        $tanggalMasuk = [
            'X RPL'   => '2025-07-01',
            'X TAV'   => '2025-07-01',
            'X TMI'   => '2025-07-01',
            'X TKR'   => '2025-07-01',
            'XI RPL'  => '2024-07-01',
            'XI TAV'  => '2024-07-01',
            'XI TMI'  => '2024-07-01',
            'XI TKR'  => '2024-07-01',
            'XII RPL' => '2023-07-01',
            'XII TAV' => '2023-07-01',
            'XII TMI' => '2023-07-01',
            'XII TKR' => '2023-07-01',
        ];
        
        foreach ($kelas as $kelasItem) {
            for ($i = 1; $i <= 25; $i++) {
                // Generate random name
                $firstNameIndex = mt_rand(0, count($namaDepan) - 1);
                $lastNameIndex = mt_rand(0, count($namaBelakang) - 1);
                $nama = $namaDepan[$firstNameIndex] . ' ' . $namaBelakang[$lastNameIndex];
                
                // Generate random 10-digit NIS (make sure it's unique)
                $nis = mt_rand(1000000000, 9999999999);
                
                // Create user
                $user = User::create([
                    'email' => strtolower(str_replace(' ', '', $nama)) . $kelasItem . $i . '@mail.com',
                    'password' => Hash::make('password123'),
                    'role' => 'siswa',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Create siswa with associated user
                Siswa::create([
                    'user_id' => $user->user_id,
                    'nama' => $nama,
                    'nis' => $nis,
                    'kelas' => $kelasItem,
                    'tanggal_masuk' => $tanggalMasuk[$kelasItem],
                    'is_aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
