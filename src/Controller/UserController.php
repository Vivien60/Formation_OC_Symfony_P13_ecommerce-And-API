<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/account', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        return $this->json(
            data:['orders' => $user->getOrders()],
            context:['groups' => ['user:read']]
        );
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
