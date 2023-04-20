<?php

namespace App\Services\v1\Order;

use App\Services\v1\Order\Repositories\OrderRepository;

class OrderService
{
    public $repository;

    /**
     * OrderService constructor.
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }
}
