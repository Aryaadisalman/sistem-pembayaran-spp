<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngsuranDu extends Model
{
    use HasFactory;

    protected $table = 'angsuran_du';
    protected $primaryKey = 'angsuran_id';

    protected $fillable = [
        'pembayaran_id',
        'siswa_id',
        'spp_id',
        'angsuran_ke',
        'nominal_angsuran',
        'jumlah_bayar',
        'status',
        'tanggal_bayar',
        'bukti_bayar',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id', 'pembayaran_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'siswa_id');
    }

    public function spp()
    {
        return $this->belongsTo(Spp::class, 'spp_id', 'spp_id');
    }
}
