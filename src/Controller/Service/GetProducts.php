<?php

namespace App\Controller\Service;

use App\Repository\ProductRepository;

class GetProducts
{
    public function __construct(private ProductRepository $productRepository)
    {

    }

    public function allWithPagination(int $page, int $maxPerPage)
    {
        $productsWithPagination = $this->productRepository->findAllWithPagination();
        $productsWithPagination->setMaxPerPage($maxPerPage);
        $productsWithPagination->setCurrentPage($page);

        return $productsWithPagination;
    }
}