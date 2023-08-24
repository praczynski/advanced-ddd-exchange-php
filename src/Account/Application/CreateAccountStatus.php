<?php

namespace App\Account\Application;

use App\Kernel\IdentityId;

class CreateAccountStatus
{
    private ?IdentityId $identityId;
    private ?string $accountNumber;
    private ?string $traderNumber;
    private string $status;

    private function __construct(string $status, ?IdentityId $identityId, ?string $accountNumber = null, ?string $traderNumber = null)
    {
        $this->status = $status;
        $this->identityId = $identityId;
        $this->accountNumber = $accountNumber;
        $this->traderNumber = $traderNumber;
    }

    public static function success(string $status, IdentityId $identityId, string $accountId, string$traderNumber): CreateAccountStatus
    {
        return new CreateAccountStatus($status, $identityId, $accountId, $traderNumber);
    }

    public static function createFailAccountStatus($status, $identityId): CreateAccountStatus
    {
        return new CreateAccountStatus($status, $identityId);
    }

    public function getIdentityId()
    {
        return $this->identityId;
    }

    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    public function getTraderNumber()
    {
        return $this->traderNumber;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
