<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstUnits extends Model
{
    use HasFactory;
    protected $table = 'master_units';
    protected $guarded=[
        'id'
    ];
}
