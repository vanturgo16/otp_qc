<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstAccountCodes extends Model
{
    use HasFactory;
    protected $table = 'master_account_codes';
    protected $guarded=[
        'id'
    ];
}
