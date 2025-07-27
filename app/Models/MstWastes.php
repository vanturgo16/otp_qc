<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstWastes extends Model
{
    use HasFactory;
    protected $table = 'master_wastes';
    protected $guarded=[
        'id'
    ];
}
