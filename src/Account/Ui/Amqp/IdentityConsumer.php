<?php

namespace App\Account\Ui\Amqp;

use App\Identity\Domain\Event\IdentityCreated;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;

class IdentityConsumer implements ConsumerInterface
{

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function execute(AMQPMessage $msg): bool|int
    {
        $data = json_decode($msg->body, true);
        $event = $this->serializer->deserialize($msg->body, IdentityCreated::class, 'json');
        print_r($event);

        return ConsumerInterface::MSG_ACK;
    }
}