<?php

namespace App\PromotionSaga;

use App\Kernel\IdentityId;
use App\Promotion\Application\PromotionService;
use PhpAmqpLib\Message\AMQPMessage;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class NewClientPromotionSaga implements ConsumerInterface {

    private SerializerInterface $serializer;
    private NewClientPromotionRepository $newClientPromotionRepository;
    private PromotionService $promotionService;

    public function __construct(
        SerializerInterface $serializer,
        NewClientPromotionRepository $newClientPromotionRepository,
        PromotionService $promotionService
    ) {
        $this->serializer = $serializer;
        $this->newClientPromotionRepository = $newClientPromotionRepository;
        $this->promotionService = $promotionService;
    }

    public function execute(AMQPMessage $msg): bool|int {

        $headers = $msg->get('application_headers');
        $nativeHeaders = $headers->getNativeData();
        $eventType = $nativeHeaders['eventType'] ?? null;

        switch ($eventType) {
            case 'IdentityCreated':
                $event = $this->serializer->deserialize($msg->body, IdentityForPromotionCreated::class, 'json');
                if (!$this->newClientPromotionRepository->findNewClientPromotion(IdentityId::fromString($event->getIdentityId()))) {
                    $newClientPromotion = new NewClientPromotion(IdentityId::fromString($event->getIdentityId()));
                    $this->newClientPromotionRepository->save($newClientPromotion);
                }
                break;

            case 'AccountActivated':
                $event = $this->serializer->deserialize($msg->body, AccountActivatedForPromotion::class, 'json');
                $newClientPromotion = $this->newClientPromotionRepository->findNewClientPromotion(IdentityId::fromString($event->getIdentityId()));
                if ($newClientPromotion) {
                    $newClientPromotion->accountActivated();
                    $this->newClientPromotionRepository->save($newClientPromotion);
                    $this->tryEndSaga($newClientPromotion);
                }
                break;

            case 'NegotiationCreated':
                $identityId = $this->serializer->deserialize($msg->body, NegotiationCreatedForPromotion::class, 'json');
                $newClientPromotion = $this->newClientPromotionRepository->findNewClientPromotion(IdentityId::fromString($identityId->getUuid()));
                if ($newClientPromotion) {
                    $newClientPromotion->negotiationCreated();
                    $this->newClientPromotionRepository->save($newClientPromotion);
                    $this->tryEndSaga($newClientPromotion);
                }
                break;

            default:
                return ConsumerInterface::MSG_REJECT;
        }

        return ConsumerInterface::MSG_ACK;
    }

    private function tryEndSaga(NewClientPromotion $newClientPromotion): void {
        if ($newClientPromotion->isComplete()) {
            $this->promotionService->createNewTraderPromotion($newClientPromotion->identityId());
        }
    }
}
