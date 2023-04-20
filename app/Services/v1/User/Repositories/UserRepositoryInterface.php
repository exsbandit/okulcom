<?php

namespace App\Services\v1\User\Repositories;

interface UserRepositoryInterface
{
    public function updateDetail($user, $attributes);
}
