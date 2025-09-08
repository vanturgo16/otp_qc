<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstWarehouses extends Model
{
    use HasFactory;
    protected $table = 'master_warehouses';
    protected $guarded=[
        'id'
    ];
}
