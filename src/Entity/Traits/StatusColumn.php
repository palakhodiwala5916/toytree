<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Serializer\Annotation\Groups;

trait StatusColumn
{
    #[Column(name: 'status', type: 'string', length: 50, nullable: false)]
    #[Groups(['api'])]
    protected string $status;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

}
