<?php

namespace App\Models\User;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\User\Subrole;

class Role extends SpatieRole
{
    public function subroles()
    {
        return $this->hasMany(Subrole::class);
    }
}