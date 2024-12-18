<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstDropdowns extends Model
{
    use HasFactory;
    protected $table = 'master_dropdowns';
    protected $guarded=[
        'id'
    ];
}
