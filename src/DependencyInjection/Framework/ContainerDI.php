<?php

namespace App\DependencyInjection\Framework;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerDI
{
    protected ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setContainerInterface(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
