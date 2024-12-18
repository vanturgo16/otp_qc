<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstAccountTypes extends Model
{
    use HasFactory;
    protected $table = 'master_account_types';
    protected $guarded=[
        'id'
    ];
}
