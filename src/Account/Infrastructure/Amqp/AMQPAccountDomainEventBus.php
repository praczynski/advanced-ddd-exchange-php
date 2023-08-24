<?php

namespace App\Account\Infrastructure\Amqp;


use App\Account\Domain\AccountDomainEventBus;
use App\Account\Domain\Events\AccountActivated;
use Exception;
use PhpAmqpLib\Wire\AMQPTable;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class AMQPAccountDomainEventBus implements AccountDomainEventBus
{
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, ProducerInterface $producer)
    {
        $this->serializer = $serializer;
        $this->producer = $producer;
    }

    public function post(AccountActivated $event): void
    {
        try {
            $headers = new AMQPTable(['eventType' => 'AccountActivated']);
            $properties = ['application_headers' => $headers];
            $jsonString = $this->serializer->serialize($event, 'json');
            $this->producer->publish($jsonString, 'accountActivatedExchange', $properties);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
