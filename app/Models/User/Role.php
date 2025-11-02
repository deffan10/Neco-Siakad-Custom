<?php

namespace App\Models\User;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\User\Subrole;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends SpatieRole
{
    use SoftDeletes;

    public function subroles()
    {
        return $this->hasMany(Subrole::class);
    }

    /**
     * The user who deleted this model (if using manual deleted_by column).
     */
    public function deletedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}