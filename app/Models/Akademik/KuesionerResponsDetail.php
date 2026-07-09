<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;

class KuesionerResponsDetail extends Model
{
    protected $table = 'kuesioner_respons_detail';
    protected $guarded = [];

    public function respons()
    {
        return $this->belongsTo(KuesionerRespons::class, 'respons_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(KuesionerPertanyaan::class, 'pertanyaan_id');
    }
}
