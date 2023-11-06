<?php

namespace App\DependencyInjection\Service;

use App\Service\Security\SecurityService;

trait SecurityServiceDI
{
    /**
     * @var SecurityService
     */
    protected $securityService;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setSecurityService(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }
}
