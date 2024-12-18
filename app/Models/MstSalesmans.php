<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstSalesmans extends Model
{
    use HasFactory;
    protected $table = 'master_salesmen';
    protected $guarded = [
        'id'
    ];

    // Definisikan relasi one-to-many ke tabel po_customer
    public function poCustomers()
    {
        return $this->hasMany(\App\Models\Marketing\InputPOCust::class, 'id_master_salesmen', 'id');
    }
}
