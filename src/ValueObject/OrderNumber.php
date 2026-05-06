<?php

namespace App\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Random\Randomizer;

#[ORM\Embeddable]
class OrderNumber implements \Stringable
{
    private const ALPHABET = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
    private const DEFAULT_PREFIX = 'ORD';
    private const DEFAULT_LENGTH = 10;
    #[ORM\Column(length: 255)]
    private readonly string $value;

    public function __construct(string $value) {
        $this->value = $value;
    }

    public static function generate(int $lengthTotal = self::DEFAULT_LENGTH, string $prefix = self::DEFAULT_PREFIX): self
    {
        if ($lengthTotal <= strlen($prefix)) {
            throw new \InvalidArgumentException("La longueur totale demandée doit etre supérieure à 0 et à la longueur du préfixe.");
        }
        $r = new Randomizer();
        return new self($prefix . $r->getBytesFromString(self::ALPHABET, $lengthTotal));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return $this->value;
    }
}