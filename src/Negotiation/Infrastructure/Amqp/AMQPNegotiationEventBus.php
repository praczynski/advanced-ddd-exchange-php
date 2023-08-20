<?php

namespace App\Negotiation\Infrastructure\Amqp;



use App\Negotiation\Domain\Event\NegotiationApproved;
use App\Negotiation\Domain\Event\NegotiationCreated;
use App\Negotiation\Domain\NegotiationDomainEventBus;
use Exception;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

class AMQPNegotiationEventBus implements NegotiationDomainEventBus
{
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, ProducerInterface $producer)
    {
        $this->serializer = $serializer;
        $this->producer = $producer;
    }

    public function postNegotiationCreated(NegotiationCreated $event): void
    {
        try {
            $identity = $event->getNegotiator()->identity(fn($identityId) => $identityId->toString());
            $jsonString = $this->serializer->serialize($identity, 'json');
            $this->producer->publish($jsonString, 'negotiationCreatedExchange');
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    public function postNegotiationApproved(NegotiationApproved $event): void
    {
    }
}




