<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KrsDetail extends Model
{
    use SoftDeletes;

    protected $table = 'krs_detail';
    protected $guarded = [];

    public function krs()
    {
        return $this->belongsTo(KrsMahasiswa::class, 'krs_id');
    }

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }
}
