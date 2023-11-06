<?php

namespace App\Entity;

use App\Entity\Traits\NameColumn;
use App\Entity\Traits\UpdatedAtColumn;
use App\Repository\ToyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ToyRepository::class)]
#[ORM\Table(name: 'toys')]
class Toy extends AbstractEntity
{
    use NameColumn;
    use UpdatedAtColumn;

    #[Column(name: 'file', type: 'string', length: 255, nullable: false)]
    #[Groups(['api'])]
    private ?string $file = null;

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }
}
