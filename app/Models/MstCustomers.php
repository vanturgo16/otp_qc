<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstCustomers extends Model
{
    use HasFactory;
    protected $table = 'master_customers';
    protected $guarded = [
        'id'
    ];

    // Definisikan relasi one-to-many ke tabel po_customer
    public function poCustomers()
    {
        return $this->hasMany(\App\Models\Marketing\InputPOCust::class, 'id_master_customers', 'id');
    }

    public function customerAddress()
    {
        return $this->belongsTo(\App\Models\MstCustomersAddress::class, 'id', 'id_master_customers');
    }

    public function currency()
    {
        return $this->belongsTo(\App\Models\MstCurrencies::class, 'id_master_currencies', 'id');
    }

    public function termPayment()
    {
        return $this->belongsTo(\App\Models\MstTermPayments::class, 'id_master_term_payments', 'id');
    }
}
