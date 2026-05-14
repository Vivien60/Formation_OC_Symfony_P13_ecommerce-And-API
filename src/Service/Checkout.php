<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Order;
use App\ValueObject\OrderNumber;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handles the checkout process by mapping a cart to an order.
 */
class Checkout
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public function createOrderFromCart(Cart $cart) : Order
    {
        $order = $this->mapCartToOrder($cart);
        $cart->emptyCart();
        $this->entityManager->persist($order);
        $this->entityManager->persist($cart);
        return $order;
    }

    /**
     * @param Order $order
     * @param Cart $cart
     * @return void
     */
    protected function mapCartToOrder(Cart $cart): Order
    {
        return Order::fromCart($cart, OrderNumber::generate());
    }
}