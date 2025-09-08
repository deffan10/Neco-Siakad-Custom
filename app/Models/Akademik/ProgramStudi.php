<?php

namespace App\Models\Akademik;

// USE SYSTEM
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
// USE MODELS
use App\Models\User;
use App\Models\Akademik\Fakultas;
use App\Models\Akademik\Kurikulum;
use App\Models\Akademik\ProgramStudiProfile;

class ProgramStudi extends Model
{
    use SoftDeletes;

    protected $table = 'program_studi';
    protected $guarded = [];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function kurikulum()
    {
        return $this->hasMany(Kurikulum::class, 'program_studi_id');
    }

    public function kelasPerkuliahan()
    {
        return $this->hasMany(\App\Models\Akademik\KelasPerkuliahan::class, 'program_studi_id');
    }

    public function kelasMahasiswa()
    {
        return $this->hasManyThrough(
            \App\Models\Akademik\KelasMahasiswa::class,
            \App\Models\Akademik\KelasPerkuliahan::class,
            'program_studi_id',
            'kelas_id',
            'id',
            'id'
        );
    }

    public function profile()
    {
        return $this->hasOne(ProgramStudiProfile::class, 'program_studi_id');
    }

    public function kaprodi()
    {
        return $this->belongsTo(User::class, 'kaprodi_id');
    }

    public function sekretaris()
    {
        return $this->belongsTo(User::class, 'sekretaris_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}