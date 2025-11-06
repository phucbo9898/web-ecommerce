<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQuantityPay extends Model
{
    protected $table = 'product_qty_pay';
    protected $fillable = [
        'product_id',
        'quantity_pay',
        'date_pay',
    ];
}
