<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Checkout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $user = $this->getUser();
        /**
         * @var User $user
         */

        return $this->render('cart/index.html.twig', [
            'cart' => $user->getCart(),
        ]);
    }

    #[Route('/cart/truncate', name: 'app_cart_truncate', methods: ['POST'])]
    #[IsCsrfTokenValid('empty-cart', tokenKey: '_token')]
    public function truncate(UserRepository $userRepository, EntityManagerInterface $manager) : Response
    {
        $user = $this->getUser();
        /**
         * @var User $user
         */
        $user->getCart()->getItems()->clear();
        $manager->flush();
        $updatedUser = $userRepository->find($user->getId());

        return $this->redirectToRoute('app_cart', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/cart/checkout', name: 'app_cart_checkout', methods: ['POST'])]
    public function checkout(Checkout $checkoutService, EntityManagerInterface $manager) : Response
    {
        $cart = $this->getUser()->getCart();

        $order = $checkoutService->createOrderFromCart(cart: $cart);

        $manager->flush();
        return $this->json(data: $order, context: ['groups' => ['order:read']]);
    }

    #[Route('/cart/add-item/{product}', name: 'app_cart_add_item', requirements: ['product' => '\d+'])]
    public function addItem(EntityManagerInterface $manager, Product $product) : Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();

        $cart->addProduct($product);
        $manager->flush();

        return $this->redirectToRoute('app_cart', [], Response::HTTP_SEE_OTHER);
    }
}
