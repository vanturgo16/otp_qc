<?php

namespace App\Models\ppic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workOrderDetails extends Model
{
    use HasFactory;
    protected $table = 'work_order_details';
    protected $guarded = [
        'id'
    ];
}
