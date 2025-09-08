<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstDepartments extends Model
{
    use HasFactory;
    protected $table = 'master_departements';
    protected $guarded=[
        'id'
    ];
}
