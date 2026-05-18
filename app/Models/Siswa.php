<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PembayaranDetail;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'siswa_id';

    protected $fillable = [
        'nama',
        'user_id',
        'nis',
        'kelas',
        'tagihan',
        'tanggal_masuk',
        'is_aktif',
        // --- kolom kenaikan kelas ---
        'tahun_ajaran',
        'tidak_naik_kelas',
        'sudah_dinaikkan',
        'lulus',
    ];

    protected $casts = [
        'tidak_naik_kelas' => 'boolean',
        'sudah_dinaikkan'  => 'boolean',
        'lulus'            => 'boolean',
        'is_aktif'         => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'siswa_id', 'siswa_id');
    }

    public function pembayaranDetail()
    {
        return $this->hasMany(PembayaranDetail::class, 'siswa_id', 'siswa_id');
    }

    public function kenaikanKelasLogs()
    {
        return $this->hasMany(KenaikanKelasLog::class, 'siswa_id', 'siswa_id');
    }
}