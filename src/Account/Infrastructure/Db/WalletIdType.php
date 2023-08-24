<?php

namespace App\Account\Infrastructure\Db;

use App\Account\Domain\AccountNumber;
use App\Account\Domain\WalletId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class WalletIdType extends Type
{
    const WALLET_ID = 'wallet_id';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'UUID';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (null === $value) ? null : WalletId::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return (null === $value) ? null : (string) $value;
    }

    public function getName()
    {
        return self::WALLET_ID;
    }
}
