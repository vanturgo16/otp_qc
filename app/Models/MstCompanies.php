<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstCompanies extends Model
{
    use HasFactory;
    protected $table = 'master_companies';
    protected $guarded=[
        'id'
    ];
}
