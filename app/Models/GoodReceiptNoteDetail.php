<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptNoteDetail extends Model
{
    use HasFactory;
    protected $table = 'good_receipt_note_details';
    protected $guarded=[
        'id'
    ];
}
