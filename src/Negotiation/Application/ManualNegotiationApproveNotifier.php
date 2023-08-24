<?php

namespace App\Negotiation\Application;

interface ManualNegotiationApproveNotifier
{
    public function notifyManualApprovalRequired();
    public function notifyNegotiationApproved(string $negotiationId);
    public function notifyNegotiationRejected(string $negotiationId);
}