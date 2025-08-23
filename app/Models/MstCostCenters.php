<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstCostCenters extends Model
{
    use HasFactory;
    protected $table = 'master_cost_centers';
    protected $guarded=[
        'id'
    ];
}
