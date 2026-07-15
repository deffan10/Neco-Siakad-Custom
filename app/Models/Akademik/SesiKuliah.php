<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SesiKuliah extends Model
{
    use SoftDeletes;
    protected $table = 'sesi_kuliah';
    protected $guarded = [];

    protected $casts = [
        'jam_mulai'  => 'string',
        'jam_selesai' => 'string',
        'is_active'  => 'boolean',
    ];
}
