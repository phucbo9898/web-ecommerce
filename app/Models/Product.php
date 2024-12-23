<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'author_id',
        'name',
        'full_name',
        'price',
        'sale',
        'description',
        'publish',
        'hot',
        'content',
        'image',
        'qty_pay',
        'quantity',
        'total_star',
    ];
}
