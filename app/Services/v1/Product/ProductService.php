<?php

namespace App\Services\v1\Product;

use App\Services\v1\Product\Repositories\ProductRepository;

class ProductService
{
    public $repository;

    /**
     * ProductService constructor.
     * @param ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }
}
