<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransDataBank extends Model
{
    use HasFactory;
    protected $table = 'data_bank_transactions';
    protected $guarded=[
        'id'
    ];
}
