<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstGroupSubs extends Model
{
    use HasFactory;
    protected $table = 'master_group_subs';
    protected $guarded=[
        'id'
    ];
}
