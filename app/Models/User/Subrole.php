<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subrole extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['role_id', 'name', 'label', 'description'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subrole')->withTimestamps();
    }
}
