<?php

namespace App\DependencyInjection\Framework;

use Symfony\Component\HttpKernel\KernelInterface;

trait EnvironmentDI
{
    protected string $environment;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setEnvironment(KernelInterface $kernel)
    {
        $this->environment = $kernel->getEnvironment();
    }

    public function isProdEnv(): bool
    {
        return $this->environment == 'prod';
    }

    public function isDevEnv(): bool
    {
        return $this->environment == 'dev';
    }

    public function isTestEnv(): bool
    {
        return $this->environment === 'test';
    }
}
