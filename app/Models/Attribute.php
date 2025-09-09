<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use Uuid;
    
    protected $fillable = [
        'name',
        'type',
        'type',
        'value',
    ];
}
