<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranMahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'pembayaran_mahasiswa';
    protected $guarded = [];

    public function tagihan()
    {
        return $this->belongsTo(TagihanMahasiswa::class, 'tagihan_id');
    }
}
