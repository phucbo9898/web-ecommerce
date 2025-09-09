<?php

namespace App\Repositories\Eloquent;

use App\Enums\PublishEnum;
use App\Models\Category;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository extends BaseRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    

    public function prepareCategory(array $data, $categoryOld = null)
    {
        $category = [
            'name' => $data['name'] ?? $categoryOld['name'] ?? null,
            'status' => PublishEnum::PUBLISHED
        ];

        return $category;
    }
}
