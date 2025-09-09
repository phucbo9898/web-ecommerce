<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'customer_name',
        'total_amount',
        'note',
        'address',
        'phone',
        'status',
        'type_payment',
        'status_payment',
        'payment_code',
    ];

    const PAYMENT_RECEIVED = 1;
    const PAYMENT_NOT_RECEIVED = 2;

    public function scopeWhereStatus(Builder $query, $value): void
    {
        $query->where('status', $value);
    }
}
