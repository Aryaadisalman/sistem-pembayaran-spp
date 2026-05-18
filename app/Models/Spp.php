<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PembayaranDetail;
use App\Models\Siswa;

class Spp extends Model
{
    use HasFactory;

    protected $table = 'spp';
    protected $primaryKey = 'spp_id';
    
    protected $fillable = [
        'nama',
        'jenis',
        'tahun_ajaran',
        'nominal',
        'max_angsuran',
        'is_aktif'
    ];

    public function pembayaranDetail()
    {
        return $this->hasMany(PembayaranDetail::class, 'spp_id', 'spp_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'siswa_id');
    }

    public function angsuranDu()
    {
        return $this->hasMany(AngsuranDu::class, 'spp_id', 'spp_id');
    }

    public function isDu(): bool
    {
        return $this->jenis === 'du';
    }
}