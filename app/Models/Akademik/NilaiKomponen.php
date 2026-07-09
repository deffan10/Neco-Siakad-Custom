<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NilaiKomponen extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_komponen';
    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_id');
    }
}
