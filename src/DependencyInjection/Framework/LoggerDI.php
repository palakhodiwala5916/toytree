<?php

namespace App\DependencyInjection\Framework;

use Psr\Log\LoggerInterface;

trait LoggerDI
{
    protected LoggerInterface $logger;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
