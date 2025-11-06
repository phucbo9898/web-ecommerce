<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Uuid;
    
    protected $fillable = [
        'name',
        'image',
        'description',
        'content',
        'publish',
        'author_id'
    ];
}
