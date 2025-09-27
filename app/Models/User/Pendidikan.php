<?php

namespace App\Models\User;

// USE SYSTEM
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
// use App\Traits\HasLogAktivitas;
// USE MODELS
use App\Models\User;

class Pendidikan extends Model
{
    use SoftDeletes;

    protected $table = 'pendidikans';
    protected $guarded = [];

    protected $casts = [
        'tahun_masuk' => 'integer',
        'tahun_lulus' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function getJenjangDisplayAttribute()
    {
        return $this->jenjang;
    }

    public function getPeriodeAttribute()
    {
        if ($this->tahun_masuk && $this->tahun_lulus) {
            return $this->tahun_masuk . ' - ' . $this->tahun_lulus;
        } elseif ($this->tahun_masuk) {
            return $this->tahun_masuk . ' - Sekarang';
        }
        return '-';
    }

    public function getJenjangBadgeClassAttribute()
    {
        switch ($this->jenjang) {
            case 'Paket C':
            case 'SMA':
            case 'SMK':
                return 'bg-secondary';
            case 'D3':
                return 'bg-info';
            case 'S1':
                return 'bg-primary';
            case 'S2':
                return 'bg-success';
            case 'S3':
                return 'bg-warning';
            default:
                return 'bg-light';
        }
    }
}