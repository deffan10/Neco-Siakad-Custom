<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifikasiInApp extends Model
{
    protected $table = 'notifikasi_inapp';
    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
