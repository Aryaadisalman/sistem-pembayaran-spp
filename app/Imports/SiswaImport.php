<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public array $errors   = [];
    public int   $success  = 0;
    public int   $skipped  = 0;

    const VALID_KELAS = [
        'X RPL 1','X RPL 2','X TAV','X TMI 1','X TMI 2',
        'X TKR 1','X TKR 2','X TKR 3','X TKR 4','X TKR 5',
        'XI RPL','XI TAV','XI TMI 1','XI TMI 2',
        'XI TKR 1','XI TKR 2','XI TKR 3','XI TKR 4','XI TKR 5',
        'XII RPL','XII TAV','XII TMI 1','XII TMI 2',
        'XII TKR 1','XII TKR 2','XII TKR 3','XII TKR 4','XII TKR 5',
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // baris 1 = header

            // Normalisasi key — hapus spasi dan lowercase
            $row = $row->mapWithKeys(fn($v, $k) => [strtolower(trim(str_replace(' ', '_', $k))) => $v]);

            $nama          = trim($row['nama']          ?? '');
            $nis           = trim($row['nis']           ?? '');
            $kelas         = trim($row['kelas']         ?? '');
            $tanggalMasuk  = trim($row['tanggal_masuk'] ?? '');
            $email         = trim($row['email']         ?? '');
            $password      = trim($row['password']      ?? '');

            // Validasi baris kosong
            if (empty($nama) && empty($nis) && empty($email)) {
                $this->skipped++;
                continue;
            }

            // Validasi field
            $rowErrors = [];

            if (empty($nama)) {
                $rowErrors[] = 'Nama wajib diisi';
            }
            if (empty($nis)) {
                $rowErrors[] = 'NIS wajib diisi';
            } elseif (Siswa::where('nis', $nis)->exists()) {
                $rowErrors[] = "NIS {$nis} sudah terdaftar";
            }
            if (empty($kelas)) {
                $rowErrors[] = 'Kelas wajib diisi';
            } elseif (!in_array($kelas, self::VALID_KELAS)) {
                $rowErrors[] = "Kelas '{$kelas}' tidak valid";
            }
            if (empty($tanggalMasuk)) {
                $rowErrors[] = 'Tanggal masuk wajib diisi';
            } else {
                try {
                    $tanggalMasuk = date('Y-m-d', strtotime($tanggalMasuk));
                    if ($tanggalMasuk === '1970-01-01') throw new \Exception();
                } catch (\Exception $e) {
                    $rowErrors[] = 'Format tanggal masuk tidak valid (gunakan YYYY-MM-DD)';
                }
            }
            if (empty($email)) {
                $rowErrors[] = 'Email wajib diisi';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = 'Format email tidak valid';
            } elseif (User::where('email', $email)->exists()) {
                $rowErrors[] = "Email {$email} sudah terdaftar";
            }
            if (empty($password)) {
                $rowErrors[] = 'Password wajib diisi';
            } elseif (strlen($password) < 3) {
                $rowErrors[] = 'Password minimal 3 karakter';
            }

            if (!empty($rowErrors)) {
                $this->errors[] = [
                    'baris'  => $rowNum,
                    'nama'   => $nama ?: '-',
                    'errors' => $rowErrors,
                ];
                continue;
            }

            // Simpan ke database
            DB::beginTransaction();
            try {
                $user = User::create([
                    'email'    => $email,
                    'password' => Hash::make($password),
                    'role'     => 'siswa',
                ]);

                Siswa::create([
                    'user_id'       => $user->user_id,
                    'nama'          => $nama,
                    'nis'           => $nis,
                    'kelas'         => $kelas,
                    'tanggal_masuk' => $tanggalMasuk,
                    'is_aktif'      => true,
                ]);

                DB::commit();
                $this->success++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = [
                    'baris'  => $rowNum,
                    'nama'   => $nama,
                    'errors' => ['Gagal simpan: ' . $e->getMessage()],
                ];
            }
        }
    }
}
