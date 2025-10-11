<?php

namespace App\Models;

// USE SYSTEM
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use App\Traits\HasLogAktivitas;
use Spatie\Permission\Traits\HasRoles;
// USE MODELS
use App\Models\User\Alamat;
use App\Models\User\Keluarga;
use App\Models\User\Pendidikan;
use App\Models\User\Subrole;
use App\Models\Referensi\Agama;
use App\Models\Referensi\GolonganDarah;
use App\Models\Referensi\JenisKelamin;
use App\Models\Referensi\Kewarganegaraan;

class User extends Authenticatable
{
    use HasRoles, HasFactory, SoftDeletes;

    protected $table = 'users';
    protected $guarded = [];


    // Shortcut

    public function getPhotoAttribute($value)
    {
        return $value == 'default.jpg' ? asset('storage/images/profile/default.jpg') : asset('storage/images/profile/' . $value);
    }

    // Relations

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id');
    }

    public function alamats()
    {
        return $this->hasMany(Alamat::class, 'user_id');
    }

    public function alamatKtp()
    {
        return $this->hasOne(Alamat::class, 'user_id')->where('tipe', 'ktp');
    }
    
    public function alamatDomisili()
    {
        return $this->hasOne(Alamat::class, 'user_id')->where('tipe', 'domisili');
    }

    // Single address access methods
    public function getAlamatKtpAttribute()
    {
        return $this->alamats()->where('tipe', 'ktp')->first();
    }

    public function getAlamatDomisiliAttribute()
    {
        return $this->alamats()->where('tipe', 'domisili')->first();
    }

    public function golonganDarah()
    {
        return $this->belongsTo(GolonganDarah::class, 'golongan_darah_id');
    }

    public function jenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jenis_kelamin_id');
    }

    public function keluargas()
    {
        return $this->hasMany(Keluarga::class, 'user_id');
    }

    public function kewarganegaraan()
    {
        return $this->belongsTo(Kewarganegaraan::class, 'kewarganegaraan_id');
    }

    public function pendidikans()
    {
        return $this->hasMany(Pendidikan::class, 'user_id');
    }

    public function subroles()
    {
        return $this->belongsToMany(Subrole::class, 'user_subroles')->withTimestamps();
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