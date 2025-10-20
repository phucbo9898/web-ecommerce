<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, Uuid;
    protected $fillable = [
        'name',
        'status',
    ];

    public function categoryAttribute()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_category', 'category_id', 'attribute_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
