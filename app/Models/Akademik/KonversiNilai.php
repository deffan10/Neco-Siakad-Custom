<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Akademik\MataKuliah;

class KonversiNilai extends Model
{
    use SoftDeletes;

    protected $table = 'konversi_nilai';
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
