<?php

namespace App\Service\Data\User;

use App\Entity\User\ConfirmationCode;
use App\Exception\SoftException;
use App\Service\Data\AbstractCRUDService;
use App\VO\Protocol\Api\User\ConfirmationCodeBody;
use App\DependencyInjection\Repository\User\ConfirmationCodeRepositoryDI;
use App\Repository\User\ConfirmationCodeRepository;

final class ConfirmationCodeService extends AbstractCRUDService
{
    use ConfirmationCodeRepositoryDI;

    public function getEntityRepository(): ConfirmationCodeRepository
    {
        return $this->confirmationCodeRepository;
    }

    /**
     * @param ConfirmationCode $confirmationCode
     * @param ConfirmationCodeBody $body
     * @throws SoftException
     */
    public function updateObjectFields($confirmationCode, $body): void
    {
        $this->validateObjectFields($confirmationCode, $body);

        // TODO: Implement updateObjectFields() method.

        $this->saveObject($confirmationCode);
    }

    /**
     * @param ConfirmationCode $confirmationCode
     * @param ConfirmationCodeBody $body
     * @throws SoftException
     */
    public function validateObjectFields($confirmationCode, $body): void
    {
        // TODO: Implement validateObjectFields() method.
    }

}
