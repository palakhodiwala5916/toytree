<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Serializer\Annotation\Groups;

trait NameColumn
{
    #[Column(name: 'name', type: 'string', nullable: false)]
    #[Groups(['api'])]
    protected ?string $name = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

}
