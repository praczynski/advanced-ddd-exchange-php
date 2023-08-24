<?php

namespace App\Quoting\Ui\Crone;



use App\Quoting\Application\QuoteApplicationService;
use phpDocumentor\Reflection\Types\Void_;

class QuoteExpirationScheduler {


    private QuoteApplicationService $quoteApplicationService;

    public function __construct(QuoteApplicationService $quoteApplicationService)
    {
        $this->quoteApplicationService = $quoteApplicationService;
    }


    protected function execute(): void
    {
        $this->quoteApplicationService->expireQuotes();
    }
}
