<?php

namespace App\DependencyInjection\Service\Data\Toy;


use App\Service\Data\Toy\ToyService;

trait ToyServiceDI
{
    protected ToyService $toyService;

    /**
     * @required
     */
    public function setToyService(ToyService $toyService)
    {
        $this->toyService = $toyService;
    }
}