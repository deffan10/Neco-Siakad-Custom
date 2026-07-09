<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;

class KuesionerPertanyaan extends Model
{
    protected $table = 'kuesioner_pertanyaan';
    protected $guarded = [];

    protected $casts = [
        'pilihan_jawaban' => 'array',
    ];

    public function kuesioner()
    {
        return $this->belongsTo(Kuesioner::class, 'kuesioner_id');
    }
}
