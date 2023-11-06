<?php

namespace App\DependencyInjection\Framework;


use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;

trait RefreshTokenGeneratorDI
{
    /**
     * @var RefreshTokenGeneratorInterface
     */
    protected $refreshTokenGenerator;

    /**
     * @param RefreshTokenGeneratorInterface $refreshTokenGenerator
     */
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setRefreshTokenGenerator(RefreshTokenGeneratorInterface $refreshTokenGenerator)
    {
        $this->refreshTokenGenerator = $refreshTokenGenerator;
    }
}