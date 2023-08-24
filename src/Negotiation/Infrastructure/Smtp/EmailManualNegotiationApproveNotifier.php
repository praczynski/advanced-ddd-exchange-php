<?php

namespace App\Negotiation\Infrastructure\Smtp;

use App\Negotiation\Application\ManualNegotiationApproveNotifier;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailManualNegotiationApproveNotifier implements ManualNegotiationApproveNotifier
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyManualApprovalRequired(): void
    {
        $email = (new Email())
            ->from('noreply@coztymit.pl')
            ->to('operator@coztymit.pl')
            ->subject('Manual Approval Required')
            ->text('Manual approval is required for a negotiation.');

        $this->mailer->send($email);
    }

    public function notifyNegotiationApproved(string $negotiationId): void
    {
        $email = (new Email())
            ->from('noreply@coztymit.pl')
            ->to('trader@coztymit.pl')
            ->subject('Your negotiation has been approved')
            ->text('Your negotiation has been approved. Negotiation number: ' . $negotiationId);

        $this->mailer->send($email);
    }

    public function notifyNegotiationRejected(string $negotiationId): void
    {
        $email = (new Email())
            ->from('noreply@coztymit.pl')
            ->to('trader@coztymit.pl')
            ->subject('Your negotiation has been rejected')
            ->text('Your negotiation has been rejected. Negotiation number: ' . $negotiationId);

        $this->mailer->send($email);
    }
}
