<?php

namespace App\Controller\Service;

use App\Repository\OrderRepository;

class GetOrders
{
    public function __construct(private OrderRepository $orderRepository)
    {

    }

    public function allWithPagination(int $page, int $maxPerPage)
    {
        $ordersWithPagination = $this->orderRepository->findAllWithPagination();
        $ordersWithPagination->setMaxPerPage($maxPerPage);
        $ordersWithPagination->setCurrentPage($page);

        return $ordersWithPagination;
    }
}