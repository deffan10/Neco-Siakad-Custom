<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class KegiatanWisuda extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan_wisuda';
    protected $guarded = [];

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(PendaftaranWisuda::class, 'kegiatan_wisuda_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
