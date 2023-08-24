<?php

namespace App\Kernel\BigDecimal;

use Brick\Math\BigDecimal as BrickBigDecimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class BigDecimalType extends Type
{
    const BIG_DECIMAL = 'big_decimal';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'DECIMAL(19, 2)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): BrickBigDecimal
    {
        return BrickBigDecimal::of($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value instanceof BrickBigDecimal) ? $value->__toString() : null;
    }

    public function getName(): string
    {
        return self::BIG_DECIMAL;
    }

}