<?php

namespace App\DataFixtures;

use App\Factory\CartFactory;
use App\Factory\OrderFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use App\ValueObject\OrderNumber;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $photos = [
            'product0.jpg', 'product1.jpg', 'product2.jpg', 'product3.jpg', 'product4.jpg', 'product5.jpg', 'product6.jpg', 'product7.jpg', 'product8.jpg'
        ];
        $products = $this->loadProducts($manager, $photos);

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
        $orders = OrderFactory::createMany($nb, function () use ($manager) {
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

    protected function loadProducts(ObjectManager $manager, array $photos) : array
    {
        $products = ProductFactory::new()->many(count($photos))->distribute(field:'picture', values:$photos)->create();

        return $products;
    }
}
