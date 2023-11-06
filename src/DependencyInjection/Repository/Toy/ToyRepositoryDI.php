<?php

namespace App\DependencyInjection\Repository\Toy;

use App\Repository\ToyRepository;

trait ToyRepositoryDI
{
    protected ToyRepository $offerRepository;

    /**
     * @required
     */
    public function setToyRepository(ToyRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }
}