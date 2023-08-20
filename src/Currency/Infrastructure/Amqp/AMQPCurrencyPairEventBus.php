<?php

namespace App\Currency\Infrastructure\Amqp;


use App\Currency\Domain\Event\CurrencyPairActivated;
use App\Currency\Domain\Event\CurrencyPairCreated;
use App\Currency\Domain\Event\CurrencyPairDeactivated;
use App\Currency\Domain\Event\CurrencyPairDomainEventBus;
use App\Currency\Domain\Event\CurrencyPairExchangeRateAdjusted;
use Symfony\Component\Serializer\SerializerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class AMQPCurrencyPairEventBus implements CurrencyPairDomainEventBus
{
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, ProducerInterface $producer)
    {
        $this->serializer = $serializer;
        $this->producer = $producer;
    }

    public function postCurrencyPairCreated(CurrencyPairCreated $event): void
    {
        $this->publish($event, 'currencyPairCreatedExchange');
    }

    public function postCurrencyPairExchangeRateAdjusted(CurrencyPairExchangeRateAdjusted $event): void
    {
        $this->publish($event, 'currencyPairRateAdjustedExchange');
    }

    public function postCurrencyPairDeactivated(CurrencyPairDeactivated $event): void
    {
        $this->publish($event, 'currencyPairDeactivatedExchange');
    }

    public function postCurrencyPairActivated(CurrencyPairActivated $event): void
    {

    }

    private function publish($event, string $exchangeName): void
    {
        try {
            $jsonString = $this->serializer->serialize($event, 'json');
            $this->producer->publish($jsonString, $exchangeName);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}
