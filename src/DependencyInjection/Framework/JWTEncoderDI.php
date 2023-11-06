<?php

namespace App\DependencyInjection\Framework;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

trait JWTEncoderDI
{
    /**
     * @var JWTEncoderInterface
     */
    protected $jwtEncoder;

    /**
     * @param JWTEncoderInterface $jwtEncoder
     */
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setJWTEncoder(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }
}