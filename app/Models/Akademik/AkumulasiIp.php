<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class AkumulasiIp extends Model
{
    use SoftDeletes;

    protected $table = 'akumulasi_ip';
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
