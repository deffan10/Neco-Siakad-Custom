<?php

namespace App\Models\Akademik;

// USE SYSTEM
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
// USE MODELS
use App\Models\User;

class TahunAkademik extends Model
{
    use SoftDeletes;

    protected $table = 'tahun_akademik';
    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function kelasPerkuliahan()
    {
        return $this->hasMany(\App\Models\Akademik\KelasPerkuliahan::class, 'tahun_akademik_id');
    }

    public function jadwalPerkuliahan()
    {
        return $this->hasMany(\App\Models\Akademik\JadwalPerkuliahan::class, 'tahun_akademik_id');
    }
}