<?php

namespace App\Models\Akademik;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EventSeminarPeserta extends Model
{
    protected $table = 'event_seminar_peserta';
    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(EventSeminar::class, 'event_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
