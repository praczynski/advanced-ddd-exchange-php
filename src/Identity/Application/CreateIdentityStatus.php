<?php

namespace App\Identity\Application;

use App\Kernel\IdentityId;

class CreateIdentityStatus
{
    private const IDENTITY_EXISTS = "IDENTITY_EXISTS";
    private const IDENTITY_CREATED = "IDENTITY_CREATED";

    private string $status;
    private ?IdentityId $identityId = null;

    private function __construct(string $status, ?IdentityId $identityId = null) {
        $this->status = $status;
        $this->identityId = $identityId;
    }

    public static function prepareSuccessStatus(IdentityId $identityId): CreateIdentityStatus {
        return new CreateIdentityStatus(self::IDENTITY_CREATED, $identityId);
    }

    public static function prepareExistsStatus(): CreateIdentityStatus {
        return new CreateIdentityStatus(self::IDENTITY_EXISTS);
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getIdentityId(): ?IdentityId {
        return $this->identityId;
    }
}