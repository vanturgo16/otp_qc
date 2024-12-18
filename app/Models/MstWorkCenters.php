<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstWorkCenters extends Model
{
    use HasFactory;
    protected $table = 'master_work_centers';
    protected $guarded = [
        'id'
    ];
}
