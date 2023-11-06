<?php


namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtColumn
{

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    protected ?\DateTime $updatedAt = null;

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    public function updatedAtColumnPrePersist():void {
        $this->updatedAt = \DateTimeService::now();
    }

    #[ORM\PreUpdate]
    public function updatedAtColumnPreUpdate():void
    {
        $this->updatedAt = \DateTimeService::now();
    }

}
