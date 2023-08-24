<?php

namespace App\Negotiation\Application;

use App\Kernel\BigDecimal\BigDecimal;

class NegotiationRateResponse {

    private string $rate;
    private ?string $status;

    public function __construct(?BigDecimal $rate) {
        if ($rate === null) {
            $this->status = "CANNOT_FIND_RATE";
        } else {
            $this->rate = $rate->toString();
            $this->status = "SUCCESS";
        }
    }

    public static function failed(): NegotiationRateResponse
    {
        return new self(null);
    }

    public function getRate():string
    {
        return $this->rate;
    }

    public function setRate($rate): void
    {
        $this->rate = $rate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

}
