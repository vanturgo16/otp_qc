<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstRequester extends Model
{
    use HasFactory;
    protected $table = 'master_requester';
    protected $guarded=[
        'id'
    ];
}
