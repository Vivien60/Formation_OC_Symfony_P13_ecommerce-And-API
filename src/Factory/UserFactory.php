<?php

namespace App\Factory;

use App\Entity\User;
use App\Faker\FakeEntityDates;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**.
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct(
        private FakeEntityDates $fakeEntityDates,
        #[Autowire('%env(DEFAULT_PWD)%')] private string $defaultPassword)
    {
    }

    #[\Override]
    public static function class(): string
    {
        return User::class;
    }

    /**
     * @return string
     */
    public function getDefaultPassword(): string
    {
        return $this->defaultPassword;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'cguAccepted' => self::faker()->boolean(),
            'email' => self::faker()->email(),
            'firstname' => self::faker()->firstName(),
            'lastname' => self::faker()->lastName(),
            'password' => $this->getDefaultPassword(),
            'roles' => [],
            ...$this->fakeEntityDates->newDates()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }
}
