<?php

namespace App\Faker;

use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

//#[AutoconfigureTag('foundry.faker_provider')]
class FakeEntityDates
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function newDates(): array|callable
    {
        //La date de mise à jour doit être postérieure à la date de création
        $updatedAt = new \DateTime();
        $createdAt = $this->faker->dateTime($updatedAt);
        return [
            'createdAt' => \DateTimeImmutable::createFromMutable($createdAt),
            'updatedAt' => \DateTimeImmutable::createFromMutable($updatedAt),
        ];
    }
}