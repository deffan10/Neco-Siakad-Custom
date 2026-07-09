<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class KuesionerRespons extends Model
{
    protected $table = 'kuesioner_respons';
    protected $guarded = [];

    public function kuesioner()
    {
        return $this->belongsTo(Kuesioner::class, 'kuesioner_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(KuesionerResponsDetail::class, 'respons_id');
    }
}
