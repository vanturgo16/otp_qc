<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lpt extends Model
{
    use HasFactory;
    protected $table = 'lpts';
    protected $guarded = ['id'];

}
