<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use App\Models\Akademik\MataKuliah;
use App\Models\Akademik\TahunAkademik;

class AsistenLabMatakuliah extends Model
{
    protected $table = 'asisten_lab_matakuliah';
    protected $guarded = [];

    public function assistant()
    {
        return $this->belongsTo(AsistenLab::class, 'asisten_lab_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
