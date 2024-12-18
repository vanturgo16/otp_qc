<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionsDetail extends Model
{
    use HasFactory;
    protected $table = 'purchase_requisition_details';
    protected $guarded=[
        'id'
    ];
}
