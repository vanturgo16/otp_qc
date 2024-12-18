<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputPOCustDetail extends Model
{
    use HasFactory;
    protected $table = 'input_po_customer_details';
    protected $guarded = [
        'id'
    ];

    public function inputPOCustomer()
    {
        return $this->belongsTo(InputPOCust::class, 'po_number');
    }

    // Definisikan relasi many-to-one ke tabel master_units
    public function masterUnit()
    {
        return $this->belongsTo(\App\Models\MstUnits::class, 'id_master_units', 'id');
    }
}
