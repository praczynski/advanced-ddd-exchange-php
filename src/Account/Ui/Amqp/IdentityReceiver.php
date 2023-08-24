<?php

namespace App\Account\Ui\Amqp;

use  App\Account\Application\AccountApplicationService;
use Exception;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

class IdentityReceiver implements ConsumerInterface
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
        try {
            $event = $this->serializer->deserialize($msg->body, IdentityCreatedEvent::class, 'json');
            $this->accountApplicationService->createAccount($event->getIdentityId());
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return ConsumerInterface::MSG_ACK;
    }
}
