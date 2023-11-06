<?php

namespace App\DependencyInjection\Service\Data\User;

use App\Service\Data\User\ConfirmationCodeService;

trait ConfirmationCodeServiceDI
{
    protected ConfirmationCodeService $confirmationCodeService;

    /**
     * @required
     */
    public function setConfirmationCodeService(ConfirmationCodeService $confirmationCodeService)
    {
        $this->confirmationCodeService = $confirmationCodeService;
    }
}
