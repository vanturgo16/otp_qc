<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStockWaste extends Model
{
    use HasFactory;

    protected $table = 'data_stock_waste';

    protected $fillable = [
        'type_product',
        'stock',
    ];
}
