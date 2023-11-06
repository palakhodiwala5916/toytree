<?php

namespace App\Service\Util;

use App\Exception\SoftException;
use App\VO\Protocol\RequestBodyInterface;
use App\VO\Protocol\ResponseDataInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator) { $this->validator = $validator; }

    /**
     * @param Constraint|Constraint[]|null $constraints
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups
     */
    public function getValidationError(object $object, array|Constraint $constraints = null, array|GroupSequence|string $groups = null): ?ConstraintViolationInterface
    {
        $errors = $this->validator->validate($object, $constraints, $groups);

        return $errors->count() > 0 ? $errors->get(0) : null;
    }

    /**
     * @param Constraint|Constraint[]|null $constraints
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups
     * @throws SoftException
     */
    public function validateRequestBody(RequestBodyInterface $body, array|Constraint $constraints = null, array|GroupSequence|string $groups = null)
    {
        if ($error = $this->getValidationError($body, $constraints, $groups)) {
            throw new SoftException($error->getMessage());
        }
    }

    /**
     * @param Constraint|Constraint[]|null $constraints
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups
     * @throws SoftException
     */
    public function validateResponseData(ResponseDataInterface $data, array|Constraint $constraints = null, array|GroupSequence|string $groups = null)
    {
        if ($error = $this->getValidationError($data, $constraints, $groups)) {
            throw new SoftException($error->getMessage());
        }
    }
}
