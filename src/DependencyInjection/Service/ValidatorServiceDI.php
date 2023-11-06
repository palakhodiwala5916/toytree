<?php

namespace App\DependencyInjection\Service;

use App\Service\Util\ValidatorService;

trait ValidatorServiceDI
{
    protected ValidatorService $validatorService;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setValidatorService(ValidatorService $validatorService)
    {
        $this->validatorService = $validatorService;
    }
}
