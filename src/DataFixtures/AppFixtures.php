<?php

namespace App\DataFixtures;

use App\Factory\CartFactory;
use App\Factory\OrderFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = ProductFactory::createMany(20);

        $carts = CartFactory::createMany(20, function () use ($manager, $products) {
            return [
                'owner' => UserFactory::new(),
            ];
        });
        foreach($carts as $cart) {
            foreach(array_rand(array:$products, num:rand(2, 5)) as $productKey) {
                $cart->addProduct($products[$productKey]);
            }
        }

       $orders = OrderFactory::createMany(20, function () use ($manager, $products) {
            return [
                'owner' => UserFactory::random(),
            ];
        });
        foreach($orders as $order) {
            foreach(array_rand(array:$products, num:rand(2, 5)) as $productKey) {
                $order->addProduct($products[$productKey]);
            }
        }

        $manager->flush();
    }
}
