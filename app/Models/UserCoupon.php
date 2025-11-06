<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    protected $fillable = [
        'coupon_id',
        'user_id',
        'date_used',
    ];
}
