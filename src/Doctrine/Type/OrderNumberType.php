<?php

namespace App\Doctrine\Type;

use App\ValueObject\OrderNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Custom Doctrine DBAL type for handling OrderNumber objects.
 *
 * This class maps the OrderNumber value object to a string for database storage
 * and converts it back to an OrderNumber object during hydration.
 */
final class OrderNumberType extends Type
{
    const NAME = 'order_number';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Converts a database value to its corresponding PHP value.
     *
     * @return OrderNumber|null The converted PHP value or null if the value is null.
     */
    public function convertToPHPValue($value, $platform) : ?OrderNumber
    {
        return $value === null ? null : new OrderNumber($value);
    }

    /**
     * Converts the given value to a format suitable for database storage.
     *
     * @return string The converted value as a string.
     */
    public function convertToDatabaseValue($value, $platform) : string
    {
        return $value->value();
    }

    /**
     * Retrieves the SQL declaration for a given column definition and platform.
     *
     * @return string The SQL declaration for the specified column and platform.
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }
}