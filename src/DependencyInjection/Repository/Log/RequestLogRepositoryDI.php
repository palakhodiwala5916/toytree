<?php

namespace App\DependencyInjection\Repository\Log;

use App\Repository\Log\RequestLogRepository;

trait RequestLogRepositoryDI
{
    protected RequestLogRepository $requestLogRepository;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setRequestLogRepository(RequestLogRepository $requestLogRepository)
    {
        $this->requestLogRepository = $requestLogRepository;
    }
}
