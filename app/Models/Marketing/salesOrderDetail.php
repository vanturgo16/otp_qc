<?php

namespace App\Models\marketing;

use App\Models\Marketing\salesOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salesOrderDetail extends Model
{
    use HasFactory;
    protected $table = 'sales_order_details';
    protected $guarded = [
        'id'
    ];

    public function salesOrders()
    {
        return $this->belongsTo(salesOrder::class, 'so_number');
    }

    // Definisikan relasi many-to-one ke tabel master_units
    public function masterUnit()
    {
        return $this->belongsTo(\App\Models\MstUnits::class, 'id_master_units', 'id');
    }
}
