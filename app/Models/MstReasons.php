<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstReasons extends Model
{
    use HasFactory;
    protected $table = 'master_reasons';
    protected $guarded=[
        'id'
    ];
}
