<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Uuid;
    protected $table = 'products';
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function productAttributeValue()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute', 'product_id', 'attribute_value_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }

    public function favoriteProduct()
    {
        return $this->belongsToMany(User::class, 'favorite_product', 'product_id', 'user_id');
    }

    public function productHitory()
    {
        return $this->hasMany(ProductHistory::class, 'product_id');
    }
}
