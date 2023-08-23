<?php

namespace App\Currency\Domain;


use App\Currency\Domain\Event\CurrencyPairExchangeRateAdjusted;
use App\Kernel\BigDecimal\BigDecimal;
use App\Kernel\Currency;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Embedded;

#[Entity]
#[Table(name:"currency_pairs")]
class CurrencyPair {

    #[Id, GeneratedValue, Column(type: "integer")]
    private int $id;

    #[Embedded(class: CurrencyPairId::class)]
    private CurrencyPairId $currencyPairId;

    #[Embedded(class: Currency::class)]
    private Currency $baseCurrency;

    #[Embedded(class: Currency::class)]
    private Currency $targetCurrency;

    #[Embedded(class: ExchangeRate::class, columnPrefix: false)]
    private ExchangeRate $exchangeRate;

    #[Embedded(class: Status::class)]
    private Status $status;

    public function __construct(CurrencyPairId $currencyPairId, Currency $baseCurrency, Currency $targetCurrency, ExchangeRate $exchangeRate) {
        $this->currencyPairId = $currencyPairId;
        $this->exchangeRate = $exchangeRate;
        $this->status = Status::ACTIVE();
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
    }

    public function adjustExchangeRate(BigDecimal $adjustedRate): void {
        $this->exchangeRate = $this->exchangeRate->adjust($adjustedRate);
    }

    public function deactivate(): void {
        $this->status = Status::INACTIVE();
    }

    public function activate(): void {
        $this->status = Status::ACTIVE();
    }

    public function currencyPairId(): CurrencyPairId {
        return $this->currencyPairId;
    }

    public function baseRate(): BigDecimal {
        return $this->exchangeRate->baseRate(fn($rate) => $rate);
    }
}