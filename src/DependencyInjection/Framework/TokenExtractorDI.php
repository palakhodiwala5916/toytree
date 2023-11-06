<?php

namespace App\DependencyInjection\Framework;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;

trait TokenExtractorDI
{
    /**
     * @var TokenExtractorInterface
     */
    protected $tokenExtractor;

    /**
     * @param TokenExtractorInterface $tokenExtractor
     */
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setTokenExtractor(TokenExtractorInterface $tokenExtractor)
    {
        $this->tokenExtractor = $tokenExtractor;
    }
}