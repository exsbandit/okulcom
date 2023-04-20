<?php


namespace App\Services\v1\User;


use App\Services\v1\User\Repositories\UserRepository;

class UserService
{
    public $repository;

    /**
     * ProductService constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
}
