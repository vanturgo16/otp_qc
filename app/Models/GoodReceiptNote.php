<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptNote extends Model
{
    use HasFactory;
    protected $table = 'good_receipt_notes';
    protected $guarded=[
        'id'
    ];
}
