<?php

namespace App\DependencyInjection\Framework;

use App\Service\Framework\RequestService;

trait RequestServiceDI
{
    protected RequestService $requestService;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setRequestService(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }
}
