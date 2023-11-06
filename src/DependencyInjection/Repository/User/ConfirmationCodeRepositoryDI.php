<?php

namespace App\DependencyInjection\Repository\User;

use App\Repository\User\ConfirmationCodeRepository;

trait ConfirmationCodeRepositoryDI
{
    protected ConfirmationCodeRepository $confirmationCodeRepository;

    /**
     * @required
     */
    public function setConfirmationCodeRepository(ConfirmationCodeRepository $confirmationCodeRepository)
    {
        $this->confirmationCodeRepository = $confirmationCodeRepository;
    }
}
