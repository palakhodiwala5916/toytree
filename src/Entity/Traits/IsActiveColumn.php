<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait IsActiveColumn
{
    #[ORM\Column(name: 'is_active', type: 'boolean', options: ['default' => true])]
    #[Groups(['api'])]
    protected bool $isActive = true;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

}
