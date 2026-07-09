<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Kuesioner extends Model
{
    use SoftDeletes;

    protected $table = 'kuesioner';
    protected $guarded = [];

    public function pertanyaans()
    {
        return $this->hasMany(KuesionerPertanyaan::class, 'kuesioner_id')->orderBy('urutan');
    }

    public function responses()
    {
        return $this->hasMany(KuesionerRespons::class, 'kuesioner_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
