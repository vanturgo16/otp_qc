<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstSupplier extends Model
{
    use HasFactory;
    protected $table = 'master_suppliers';
    protected $guarded=[
        'id'
    ];
}
