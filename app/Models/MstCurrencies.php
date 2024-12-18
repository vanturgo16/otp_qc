<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstCurrencies extends Model
{
    use HasFactory;
    protected $table = 'master_currencies';
    protected $guarded=[
        'id'
    ];
}
