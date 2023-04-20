<?php

namespace App\Services\v1\Order\Repositories;

use App\Models\Order;

interface OrderRepositoryInterface
{
    /**
     * @param Order $order
     * @return mixed
     */
    public function approve(Order $order);

    /**
     * @param Order $order
     * @return mixed
     */
    public function reject(Order $order);
}
