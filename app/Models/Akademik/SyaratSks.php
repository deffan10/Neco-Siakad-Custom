<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyaratSks extends Model
{
    use SoftDeletes;

    protected $table = 'syarat_sks';
    protected $guarded = [];
}
