<?php


namespace App\DependencyInjection\Framework;


use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

trait JWTTokenManagerDI
{
    /**
     * @var JWTTokenManagerInterface
     */
    protected $jwtTokenManager;

    /**
     * @param JWTTokenManagerInterface $jwtTokenManager
     */
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setJWTTokenManager(JWTTokenManagerInterface $jwtTokenManager)
    {
        $this->jwtTokenManager = $jwtTokenManager;
    }
}
