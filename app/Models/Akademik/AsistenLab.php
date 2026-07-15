<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class AsistenLab extends Model
{
    use SoftDeletes;

    protected $table = 'asisten_lab';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignments()
    {
        return $this->hasMany(AsistenLabMatakuliah::class, 'asisten_lab_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
