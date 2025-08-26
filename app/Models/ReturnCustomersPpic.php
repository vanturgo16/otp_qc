<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnCustomersPpic extends Model
{
    use HasFactory;

    protected $table = 'return_customers_ppic';

    protected $fillable = [
    'id_delivery_note_details',
    'id_delivery_notes',
    'id_master_customers',
    'no_po',
    'id_sales_orders',
    'name',
    'qty',
    'id_master_units',
    'tanggal',
    'berat',
    'keterangan',
];
}
