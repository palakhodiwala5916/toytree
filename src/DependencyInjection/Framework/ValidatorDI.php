<?php

namespace App\DependencyInjection\Framework;

use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidatorDI
{
    protected ValidatorInterface $validator;

    /**
     * @param ValidatorInterface $validator
     */
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setValidatorInterface(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
}
