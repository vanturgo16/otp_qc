<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitions extends Model
{
    use HasFactory;
    protected $table = 'purchase_requisitions';
    protected $guarded=[
        'id'
    ];

     // Definisikan relasi many-to-one ke tabel master_salesman
     public function masterSupplier()
     {
         return $this->belongsTo(\App\Models\MstSupplier::class, 'id_master_suppliers', 'id');
     }
}
