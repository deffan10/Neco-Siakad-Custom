<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class PesanInternal extends Model
{
    use SoftDeletes;

    protected $table = 'pesan_internal';
    protected $guarded = [];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
}
