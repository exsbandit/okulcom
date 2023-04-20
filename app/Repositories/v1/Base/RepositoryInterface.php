<?php

namespace App\Repositories\v1\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function prepareQuery(array $filters = [], array $select = [], array $with = [], null|string $sortField = null, string $sort = 'asc', bool $withTrashed = false): mixed;

    public function get(array $filters = [], array $select = [], array $with = [], null|string $sortField = null, string $sort = 'asc', bool $withTrashed = false): Collection;

    public function paginate(array $filters = [], array $select = [], array $with = [], null|string $sortField = null, string $sort = 'asc', bool $withTrashed = false, int $pageSize = 24): LengthAwarePaginator;

    public function create(array $attributes): Model;

    public function update(Model $model, array $attributes): Model;

    public function delete(Model $model): bool;

    public function find(int $id, array $with = []): Model;

    public function getModelClass(): string;
}
