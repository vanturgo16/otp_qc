<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailGoodReceiptNoteDetail extends Model
{
    use HasFactory;
    protected $table = 'detail_good_receipt_note_details';
    protected $guarded=[
        'id'
    ];
}
