<?php

namespace App\Account\Infrastructure\Db;

use App\Account\Domain\AccountNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class AccountNumberType extends Type
{
    const ACCOUNT_NUMBER = 'account_number';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'UUID';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (null === $value) ? null : AccountNumber::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return (null === $value) ? null : (string) $value;
    }

    public function getName()
    {
        return self::ACCOUNT_NUMBER;
    }
}
