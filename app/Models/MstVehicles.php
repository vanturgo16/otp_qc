<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstVehicles extends Model
{
    use HasFactory;
    protected $table = 'master_vehicles';
    protected $guarded=[
        'id'
    ];
}
