<?php

namespace App\Service\Data\Toy;

use App\Entity\Toy;
use App\Exception\SoftException;
use App\Repository\ToyRepository;
use App\Service\Data\AbstractCRUDService;
use App\DependencyInjection\Repository\Toy\ToyRepositoryDI;

final class ToyService extends AbstractCRUDService
{
    use ToyRepositoryDI;

    public function getEntityRepository(): ToyRepository
    {
        return $this->offerRepository;
    }

    /**
     * @param Toy $offer
     * @throws SoftException
     */
    public function updateObjectFields($offer, $body): void
    {
        $this->validateObjectFields($offer, $body);

        // TODO: Implement updateObjectFields() method.

        $this->saveObject($offer);
    }

    /**
     * @param Toy $offer
     * @throws SoftException
     */
    public function validateObjectFields($offer, $body): void
    {
        // TODO: Implement validateObjectFields() method.
    }


}