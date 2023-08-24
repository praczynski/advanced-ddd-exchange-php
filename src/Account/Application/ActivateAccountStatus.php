<?php

namespace App\Account\Application;

class ActivateAccountStatus {
    private string $status;

    private function __construct($status) {
        $this->status = $status;
    }

    public static function successStatus(): ActivateAccountStatus
    {
        return new ActivateAccountStatus("SUCCESS");
    }

    public static function failStatus(): ActivateAccountStatus
    {
        return new ActivateAccountStatus("FAIL");
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}