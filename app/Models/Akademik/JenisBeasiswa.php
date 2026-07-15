<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class JenisBeasiswa extends Model
{
    use SoftDeletes;
    protected $table = 'jenis_beasiswa';
    protected $guarded = [];

    public function penerimas()
    {
        return $this->hasMany(BeasiswaMahasiswa::class, 'jenis_beasiswa_id');
    }
}
