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

        $carts = $this->loadCartsWithNewUsers($manager, $products, 20);

        $orders = $this->loadOrders($manager, $products, 20);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param array $products
     * @return Cart[]|array
     */
    protected function loadCartsWithNewUsers(ObjectManager $manager, array $products, int $nb): array
    {
        $carts = CartFactory::createMany($nb, function () use ($manager) {
            return [
                'owner' => UserFactory::new(),
            ];
        });
        foreach ($carts as $cart) {
            foreach (array_rand(array: $products, num: rand(2, 5)) as $productKey) {
                $cart->addProduct($products[$productKey]);
            }
        }
        return $carts;
    }

    /**
     * @param ObjectManager $manager
     * @param array $products
     * @return Order[]|array
     */
    protected function loadOrders(ObjectManager $manager, array $products, int $nb): array
    {
        $orders = OrderFactory::createMany($nb, function () use ($manager, $products) {
            return [
                'owner' => UserFactory::random(),
            ];
        });
        foreach ($orders as $order) {
            foreach (array_rand(array: $products, num: rand(2, 5)) as $productKey) {
                $order->addProduct($products[$productKey]);
            }
            $order->computeTotalPrice();
        }

        return $orders;
    }
}
