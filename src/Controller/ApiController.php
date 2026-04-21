<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\PagerConfiguratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController
{
    #[Route('/api/products', name: 'app_api_products', methods: ['GET'])]
    public function products(Request $request, ProductRepository $repository, PagerConfiguratorService $pagerConfigurator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagedProducts = $repository->findAllWithPagination();
        $pagerConfigurator->configure($pagedProducts, $page, 5);
        return $this->json(data: $pagedProducts);
    }
}
