<?php

namespace App\Doctrine\Type;

use App\ValueObject\OrderNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class OrderNumberType extends Type
{
    const NAME = 'order_number';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, $platform) : ?OrderNumber
    {
        return $value === null ? null : new OrderNumber($value);
    }

    public function convertToDatabaseValue($value, $platform) : string
    {
        return $value->value();
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }
}