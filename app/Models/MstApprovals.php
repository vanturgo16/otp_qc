<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstApprovals extends Model
{
    use HasFactory;
    protected $table = 'master_approvals';
    protected $guarded=[
        'id'
    ];
}
