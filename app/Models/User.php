<?php

namespace App\Models;

// USE SYSTEM
use App\Models\Referensi\Agama;
use App\Models\Referensi\GolonganDarah;
use App\Models\Referensi\JenisKelamin;
// use App\Traits\HasLogAktivitas;
use App\Models\Referensi\Kewarganegaraan;
// USE MODELS
use App\Models\User\Alamat;
use App\Models\User\Keluarga;
use App\Models\User\Pendidikan;
use App\Models\User\Subrole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $table = 'users';

    protected $guarded = [];

    // Shortcut

    public function getPhotoAttribute($value)
    {
        return $value == 'default.jpg' ? asset('storage/images/profile/default.jpg') : asset('storage/images/profile/'.$value);
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

    // ============ ROLE-SPECIFIC DATA RELATIONSHIPS ============

    public function dataMahasiswa()
    {
        return $this->hasOne(\App\Models\Akademik\DataMahasiswa::class, 'user_id');
    }

    public function dataKaryawan()
    {
        return $this->hasOne(\App\Models\User\DataKaryawan::class, 'user_id');
    }

    public function dataDosen()
    {
        return $this->hasOne(\App\Models\User\DataDosen::class, 'user_id');
    }

    public function dataPestaPMB()
    {
        return $this->hasOne(\App\Models\Akademik\DataPestaPMB::class, 'user_id');
    }

    public function dataAlumni()
    {
        return $this->hasOne(\App\Models\Akademik\DataAlumni::class, 'user_id');
    }
}
