<?php

namespace App\Services\v1\User\Repositories;

use App\Models\User;
use App\Repositories\v1\Base\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
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

    public function updateDetail($user, $attributes)
    {
        $user->detail()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'],
                'phone_number' => preg_replace('/[^0-9]/', '', $attributes['phone_number']),
            ]
        );

        return User::where('id', $user->id)->get();
    }
}
