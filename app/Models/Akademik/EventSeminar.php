<?php

namespace App\Models\Akademik;

use App\Models\User;
use App\Models\Akademik\TahunAkademik;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventSeminar extends Model
{
    use SoftDeletes;

    protected $table = 'events_seminar';
    protected $guarded = [];

    protected $casts = [
        'tanggal'   => 'date',
        'is_wajib'  => 'boolean',
        'is_open'   => 'boolean',
    ];

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function pesertaMahasiswas()
    {
        return $this->hasMany(EventSeminarPeserta::class, 'event_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
