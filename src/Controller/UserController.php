<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/account', name: 'app_user')]
    public function index(Service\GetOrders $getOrders): Response
    {
        $orders = $getOrders->allWithPagination(page: 1, maxPerPage: 9);
        return $this->json(
            data:['orders' => $orders, $orders],
            context:['groups' => ['order:read']]
        );
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
