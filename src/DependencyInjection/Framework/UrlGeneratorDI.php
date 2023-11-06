<?php

namespace App\DependencyInjection\Framework;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait UrlGeneratorDI
{
    protected UrlGeneratorInterface $urlGenerator;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
}