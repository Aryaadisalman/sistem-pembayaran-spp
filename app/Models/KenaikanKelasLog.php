<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KenaikanKelasLog extends Model
{
    protected $table = 'kenaikan_kelas_logs';

    protected $fillable = [
        'siswa_id',
        'nama_siswa',
        'kelas_lama',
        'kelas_baru',
        'tahun_ajaran',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'siswa_id');
    }
}
