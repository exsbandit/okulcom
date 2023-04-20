<?php

namespace App\Services\v1\Category;

use App\Services\v1\Category\Repositories\CategoryRepository;

class CategoryService
{
    public $repository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
}
