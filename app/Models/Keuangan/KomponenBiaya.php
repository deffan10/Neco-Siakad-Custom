<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KomponenBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'komponen_biaya';
    protected $guarded = [];

    public function tarifs()
    {
        return $this->hasMany(TarifBiaya::class, 'komponen_id');
    }
}
