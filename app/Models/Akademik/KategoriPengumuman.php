<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class KategoriPengumuman extends Model
{
    protected $table = 'kategori_pengumuman';
    protected $guarded = [];
    public $timestamps = true;

    public function pengumumans()
    {
        return $this->hasMany(Pengumuman::class, 'kategori_id');
    }
}
