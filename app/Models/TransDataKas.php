<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransDataKas extends Model
{
    use HasFactory;
    protected $table = 'data_kas_transactions';
    protected $guarded=[
        'id'
    ];
}
