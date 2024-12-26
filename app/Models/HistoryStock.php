<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    use HasFactory;
    protected $table = 'history_stocks';
    protected $guarded=[
        'id'
    ];
}
