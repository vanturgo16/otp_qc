<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstTermPayments extends Model
{
    use HasFactory;
    protected $table = 'master_term_payments';
    protected $guarded = [
        'id'
    ];

    // Definisikan relasi one-to-many ke tabel po_customer
    public function poCustomers()
    {
        return $this->hasMany(\App\Models\Marketing\InputPOCust::class, 'id_master_term_payments', 'id');
    }
}
