<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    protected $table = 'product_history';
    protected $fillable = [
        'product_id',
        'number_import',
        'number_export',
        'time_import',
        'time_export',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
