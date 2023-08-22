<?php

namespace App\Identity\Infrastructure\Amqp;

use App\Identity\Domain\Event\IdentityCreated;
use Exception;
use http\Exception\RuntimeException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use PhpAmqpLib\Wire\AMQPTable;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class AMQPIdentityEventBus
{
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, ProducerInterface $producer)
    {
        $this->serializer = $serializer;
        $this->producer = $producer;
    }


    //Do użycia w metodzie post z klasy IdentityDomainEventBus
    //$jsonString = $this->serializer->serialize($event, 'json');
    //$headers = new AMQPTable(['eventType' => 'IdentityCreated']);
    //$properties = ['application_headers' => $headers];
    //$this->producer->publish($jsonString, 'identityCreatedExchange', $properties);

}