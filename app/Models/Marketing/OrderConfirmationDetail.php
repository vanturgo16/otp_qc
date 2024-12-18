<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderConfirmationDetail extends Model
{
    use HasFactory;
    protected $table = 'order_confirmation_details';
    protected $guarded = [
        'id'
    ];

    public function orderConfirmation()
    {
        return $this->belongsTo(orderConfirmation::class, 'oc_number');
    }

    // Definisikan relasi many-to-one ke tabel master_units
    public function masterUnit()
    {
        return $this->belongsTo(\App\Models\MstUnits::class, 'id_master_units', 'id');
    }
}
