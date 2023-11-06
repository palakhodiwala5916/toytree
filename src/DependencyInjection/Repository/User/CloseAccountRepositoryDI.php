<?php

namespace App\DependencyInjection\Repository\User;

use App\Repository\User\CloseAccountRepository;

trait CloseAccountRepositoryDI
{
    protected CloseAccountRepository $closeAccountRepository;

    /**
     * @required
     */
    public function setCloseAccountRepository(CloseAccountRepository $closeAccountRepository)
    {
        $this->closeAccountRepository = $closeAccountRepository;
    }
}
