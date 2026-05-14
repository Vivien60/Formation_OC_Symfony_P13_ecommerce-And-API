<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\PagerConfiguratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'app_api_')]
final class ApiController extends AbstractController
{
    /**
     * Handles the retrieval and pagination of products.
     * @return Response Returns a JSON response with paginated product data.
     */
    #[Route('/products', name: 'products', methods: ['GET'])]
    public function products(Request $request, ProductRepository $repository, PagerConfiguratorService $pagerConfigurator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagedProducts = $repository->findAllWithPagination();
        $pagerConfigurator->configure($pagedProducts, $page, 5);
        return $this->json(data: $pagedProducts, context: ['groups' => ['product:read']]);
    }
}
