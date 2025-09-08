<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnCustomersPpic extends Model
{
    use HasFactory;

    protected $table = 'return_customers_ppic';

   protected $guarded = ['id'];
}
