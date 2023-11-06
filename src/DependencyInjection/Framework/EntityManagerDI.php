<?php

namespace App\DependencyInjection\Framework;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerDI
{
    protected EntityManagerInterface $entityManager;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
