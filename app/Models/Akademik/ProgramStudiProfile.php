<?php

namespace App\Models\Akademik;

// USE SYSTEM
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
// USE MODELS
use App\Models\User;
use App\Models\Akademik\ProgramStudi;

class ProgramStudiProfile extends Model
{
    use SoftDeletes;

    protected $table = 'program_studi_profile';
    protected $guarded = [];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    public function getLogoAttribute($value)
    {
        return $value == 'default.png' ? asset('storage/images/logo/logo-vertikal.png') : asset('storage/images/logo/prodi/' . $value);
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