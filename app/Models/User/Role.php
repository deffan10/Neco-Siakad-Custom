<?php

namespace App\Models\User;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\User\Subrole;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends SpatieRole
{
    use SoftDeletes;

    public function subroles()
    {
        return $this->hasMany(Subrole::class);
    }
}