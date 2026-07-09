<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Akademik\ProgramStudi;

class TarifBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'tarif_biaya';
    protected $guarded = [];

    public function komponen()
    {
        return $this->belongsTo(KomponenBiaya::class, 'komponen_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }
}
