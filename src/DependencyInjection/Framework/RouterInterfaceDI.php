<?php

namespace App\DependencyInjection\Framework;

use Symfony\Component\Routing\RouterInterface;

trait RouterInterfaceDI
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param RouterInterface $router
     */
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setRouterInterface(RouterInterface $router)
    {
        $this->router = $router;
    }
}