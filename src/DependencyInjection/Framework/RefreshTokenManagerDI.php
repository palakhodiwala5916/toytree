<?php

namespace App\DependencyInjection\Framework;



use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;

trait RefreshTokenManagerDI
{
    /**
     * @var RefreshTokenManagerInterface
     */
    protected $refreshTokenManager;

    /**
     * @param RefreshTokenManagerInterface $refreshTokenManager
     */
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setRefreshTokenManager(RefreshTokenManagerInterface $refreshTokenManager)
    {
        $this->refreshTokenManager = $refreshTokenManager;
    }
}
