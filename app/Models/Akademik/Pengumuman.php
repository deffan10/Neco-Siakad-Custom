<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Pengumuman extends Model
{
    use SoftDeletes;
    protected $table = 'pengumuman';
    protected $guarded = [];

    protected $casts = [
        'is_published'  => 'boolean',
        'published_at'  => 'datetime',
    ];

    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPengumuman::class, 'kategori_id');
    }
}
