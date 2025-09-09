<?php

namespace App\Repositories\Eloquent;

use App\Models\Attribute;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AttributeRepository extends BaseRepository
{
    protected $model;

    public function __construct(Attribute $model)
    {
        $this->model = $model;
    }

    public function with($relations)
    {
        return $this->model->with($relations);
    }

    // public function paginate()
    // {
    //     return $this->model->paginate(10);
    // }
    public function prepareArticle(array $data)
    {
        $article = [
            'name' => $data['name'] ?? '',
            'slug' => Str::slug($data['name'] ?? ''),
            'description' => $data['description'] ?? '',
            'content' => $data['content'] ?? '',
            'image' => $data['image'] ?? '',
            'author_id' => Auth::user()->id ?? ''
        ];

        return $article;
    }
}
