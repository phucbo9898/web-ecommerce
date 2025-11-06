<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    const FIVE_STAR = 5;
    const FOUR_STAR = 4;
    const THREE_STAR = 3;
    const TWO_STAR = 2;
    const ONE_STAR = 1;

    protected $fillable = [
        'user_id',
        'product_id',
        'number_star',
        'comment',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
