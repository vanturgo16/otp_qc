<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstGroups extends Model
{
    use HasFactory;
    protected $table = 'master_groups';
    protected $guarded=[
        'id'
    ];
}
