<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Checkout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CartController extends AbstractController
{
    /**
     * Display the user's cart with the items
     */
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

    /**
     * Handles the truncation of the user's cart by clearing all items.
     *
     * This action is protected by CSRF validation for security and is triggered
     * via a POST request to the specified route.
     *
     * @return Response Redirects to the cart route after clearing the cart.
     */
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

    /**
     * Handles the checkout process for the items in the user's cart.
     *
     * This method creates an order based on the current user's cart,
     * It also redirects the user to their account page
     *
     * @return Response A redirect response to the user's account
     */
    #[Route('/cart/checkout', name: 'app_cart_checkout', methods: ['POST'])]
    #[IsCsrfTokenValid('checkout-cart', tokenKey: '_token')]
    public function checkout(Checkout $checkoutService, EntityManagerInterface $manager, TranslatorInterface $translator) : Response
    {
        $cart = $this->getUser()->getCart();
        $order = $checkoutService->createOrderFromCart(cart: $cart);
        $manager->flush();

        $this->addFlash('success', $translator->trans('flash.order.checkout.success', ['%number%' => (string) $order->getNumero()]));

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Handles adding a product to the user's shopping cart.
     *
     * Validates a CSRF token to ensure request integrity.
     *
     * Retrieves the quantity of the product to add from the request,
     * updates the shopping cart with this quantity, or removes the product if the quantity is zero or under.
     *
     * Persists the updated cart to the database and redirects the user to the shopping cart page.
     *
     * @return Response A redirection response to the shopping cart page.
     */
    #[Route('/cart/add-item/{product}', name: 'app_cart_add_item', requirements: ['product' => '\d+', 'quantity' => '.*'], methods: ['POST'])]
    #[IsCsrfTokenValid('add-to-cart', tokenKey: '_token')]
    public function addItem(EntityManagerInterface $manager, Product $product, Request $request) : Response
    {
        $quantity = (int)$request->request->get('quantity');
        $user = $this->getUser();
        /**
         * @var User $user
         */
        $cart = $user->getCart();
        $cart->setProductQuantityOrRemove(product:$product, newQuantity:$quantity);

        $manager->flush();

        return $this->redirectToRoute('app_cart', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);
    }
}
