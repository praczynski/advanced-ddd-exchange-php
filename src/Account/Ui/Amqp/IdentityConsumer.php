<?php

namespace App\Account\Ui\Amqp;

use App\Account\Application\AccountApplicationService;
use App\Identity\Domain\Event\IdentityCreated;
use App\Kernel\IdentityId;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;

class IdentityConsumer implements ConsumerInterface
{

    private SerializerInterface $serializer;
    private AccountApplicationService $accountApplicationService;

    public function __construct(SerializerInterface $serializer, AccountApplicationService $accountApplicationService)
    {
        $this->serializer = $serializer;
        $this->accountApplicationService = $accountApplicationService;
    }

    public function execute(AMQPMessage $msg): bool|int
    {
        $data = json_decode($msg->body, true);
        $event = $this->serializer->deserialize($msg->body, IdentityCreated::class, 'json');
        $this->accountApplicationService->createAccount(IdentityId::fromString($event->getIdentityId()));
        return ConsumerInterface::MSG_ACK;
    }
}