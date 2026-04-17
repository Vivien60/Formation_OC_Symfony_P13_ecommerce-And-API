<?php

namespace App\Controller;

use App\Controller\Service\GetProducts;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function home(GetProducts $getProducts): Response
    {
        $products = $getProducts->allWithPagination(1, 9);
        return $this->json($products);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
