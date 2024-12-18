<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderConfirmation extends Model
{
    use HasFactory;
    protected $table = 'order_confirmations';
    protected $guarded = [
        'id'
    ];

    public function masterCustomerAddress()
    {
        return $this->hasMany(\App\Models\MstCustomersAddress::class, 'id_master_customers', 'id_master_customers');
    }

    // Definisikan relasi many-to-one ke tabel master_customers
    public function masterCustomer()
    {
        return $this->belongsTo(\App\Models\MstCustomers::class, 'id_master_customers', 'id');
    }

    // Definisikan relasi many-to-one ke tabel master_salesman
    public function masterSalesman()
    {
        return $this->belongsTo(\App\Models\MstSalesmans::class, 'id_master_salesmen', 'id');
    }

    // Definisikan relasi many-to-one ke tabel master_term_payments
    public function masterTermPAyment()
    {
        return $this->belongsTo(\App\Models\MstTermPayments::class, 'id_master_term_payments', 'id');
    }

    // Definisikan relasi many-to-one ke tabel master_currencies
    public function masterCurrencies()
    {
        return $this->belongsTo(\App\Models\MstCurrencies::class, 'id_master_currencies', 'id');
    }

    // Definisikan relasi one-to-many ke tabel po_customer_details
    public function orderConfirmationDetails()
    {
        return $this->hasMany(OrderConfirmationDetail::class, 'oc_number', 'oc_number');
    }
}
