<?php

namespace App\Services\v1\Category\Repositories;

use App\Models\Category;
use App\Repositories\v1\Base\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function prepareFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $query->where('name', 'LIKE', "{$filters['name']}%");
            unset($filters['name']);
        }

        return parent::prepareFilters($query, $filters);
    }
}
