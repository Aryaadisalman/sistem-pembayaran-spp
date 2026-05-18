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
            'email' => 'adminypt@gmail.com',
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

        
        
        
        
    }
}
