<?php

namespace App\Factory;

use App\Entity\Cart;
use App\Faker\FakeEntityDates;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Cart>
 */
final class CartFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     */
    public function __construct(private FakeEntityDates $fakeEntityDates)
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Cart::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            ...$this->fakeEntityDates->newDates(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Cart $cart): void {})
        ;
    }
}
