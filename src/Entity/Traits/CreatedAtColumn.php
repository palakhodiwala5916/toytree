<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtColumn
{
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?\DateTime $createdAt = null;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = \DateTimeService::now();
    }

}
