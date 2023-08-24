<?php

namespace App\Currency\Infrastructure\Amqp;


use App\Currency\Domain\Event\CurrencyPairActivated;
use App\Currency\Domain\Event\CurrencyPairCreated;
use App\Currency\Domain\Event\CurrencyPairDeactivated;
use App\Currency\Domain\Event\CurrencyPairDomainEventBus;
use App\Currency\Domain\Event\CurrencyPairExchangeRateAdjusted;
use Exception;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class AMQPCurrencyPairEventBus implements CurrencyPairDomainEventBus
{
    private ProducerInterface $createdProducer;
    private ProducerInterface $adjustedProducer;
    private ProducerInterface $deactivatedProducer;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer,  ProducerInterface $createdProducer, ProducerInterface $adjustedProducer, ProducerInterface $deactivatedProducer)
    {
        $this->serializer = $serializer;
        $this->createdProducer = $createdProducer;
        $this->adjustedProducer = $adjustedProducer;
        $this->deactivatedProducer = $deactivatedProducer;

    }

    public function postCurrencyPairCreated(CurrencyPairCreated $event): void
    {
        $this->publish($this->createdProducer, $event);
    }

    public function postCurrencyPairExchangeRateAdjusted(CurrencyPairExchangeRateAdjusted $event): void
    {
        $this->publish($this->adjustedProducer, $event);
    }

    public function postCurrencyPairDeactivated(CurrencyPairDeactivated $event): void
    {
        $this->publish($this->deactivatedProducer, $event);
    }

    public function postCurrencyPairActivated(CurrencyPairActivated $event): void
    {

    }

    private function publish(ProducerInterface $producer, $event): void
    {
        try {
            $jsonString = $this->serializer->serialize($event, 'json');
            $producer->publish($jsonString);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
