<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use Uuid;
    
    protected $fillable = [
        'name',
        'image',
        'url',
    ];
}
