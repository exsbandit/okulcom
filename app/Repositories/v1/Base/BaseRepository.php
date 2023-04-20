<?php

namespace App\Repositories\v1\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * @package App\Repositories\v1\Base
 */
class BaseRepository implements RepositoryInterface
{
    /**
     * BaseRepository constructor.
     * @param Model $model
     */

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return get_class($this->model);
    }

    public function prepareFilters(\Illuminate\Database\Eloquent\Builder $query, array $filters): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where($filters);
    }

    public function prepareQuery(array $filters = [], array $select = ['*'], array $with = [], ?string $sortField = null, string $sort = 'asc', bool $withTrashed = false): mixed
    {
        $query = $this->model::query();

        return $this
            ->prepareFilters($query, $filters)
            ->when($withTrashed, function ($query) {
                $query->withTrashed();
            })
            ->select($select)
            ->with($with)
            ->when($sortField, function ($query, $sortField) use ($sort) {
                $query->orderBy($sortField, $sort);
            });
    }

    /**
     * Tüm liste
     *
     * @param array $filters
     * @param array|string[] $select
     * @param array $with
     * @param string|null $sortField
     * @param string $sort
     * @param bool $withTrashed
     * @return Collection
     */
    public function get(array $filters = [], array $select = ['*'], array $with = [], ?string $sortField = null, string $sort = 'asc', bool $withTrashed = false): Collection
    {
        return $this->prepareQuery($filters, $select, $with, $sortField, $sort, $withTrashed)->get();
    }

    /**
     * Sayfalanmış liste
     *
     * @param array $filters
     * @param array|string[] $select
     * @param array $with
     * @param string|null $sortField
     * @param string $sort
     * @param bool $withTrashed
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function paginate(array $filters = [], array $select = ['*'], array $with = [], ?string $sortField = null, string $sort = 'asc', bool $withTrashed = false, int $pageSize = 24): LengthAwarePaginator
    {
        return $this->prepareQuery($filters, $select, $with, $sortField, $sort, $withTrashed)->paginate($pageSize);
    }

    /**
     * Oluşturma
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model::query()->create($attributes);
    }

    /**
     * Güncelleme
     *
     * @param Model $model
     * @param array $attributes
     * @return Model
     */
    public function update(Model $model, array $attributes): Model
    {
        $model->fill($attributes)->save();

        return $this->find($model->id);
    }

    /**
     * Silme
     *
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Bulma
     *
     * @param int $id
     * @param array $with
     * @return Model
     */
    public function find(int $id, array $with = []): Model
    {
        return $this->model::query()
            ->with($with)
            ->findOrFail($id);
    }
}
