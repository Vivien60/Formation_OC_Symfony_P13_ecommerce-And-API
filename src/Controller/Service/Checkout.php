<?php

namespace App\Controller\Service;

use App\Entity\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class Checkout
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public function createOrderFromCart(Cart $cart) : Order
    {
        $order = new Order();
        $this->mapCartToOrder($order, $cart);
        $cart->getItems()->clear();
        $this->entityManager->persist($order);
        $this->entityManager->persist($cart);
        return $order;
    }

    /**
     * @param Order $order
     * @param Cart $cart
     * @return void
     */
    protected function mapCartToOrder(Order $order, Cart $cart): void
    {
        $order->setOwner($cart->getOwner());
        foreach ($cart->getItems() as $cartItem) {
            $order->addProduct($cartItem->getProduct(), $cartItem->getQuantity());
        }
    }
}