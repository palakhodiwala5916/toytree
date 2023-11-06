<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Serializer\Annotation\Groups;

trait DescriptionColumn
{
    #[Column(name: 'description', type: 'text', nullable: true)]
    #[Groups(['api'])]
    protected ?string $description = null;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

}
