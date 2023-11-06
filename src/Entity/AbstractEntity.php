<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtColumn;
use App\Entity\Traits\UuidColumn;
use App\VO\Protocol\ResponseDataInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractEntity implements ResponseDataInterface
{
    use UuidColumn;
    use CreatedAtColumn;

    public function __construct()
    {
        $this->createdAt = \DateTimeService::now();
    }

    public function __toString(): string
    {
        return $this->id ?? 'unknown object id';
    }
}
