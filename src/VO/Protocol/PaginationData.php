<?php

namespace App\VO\Protocol;

use Symfony\Component\Serializer\Annotation\Groups;

class PaginationData implements ResponseDataInterface
{
    #[Groups(['api'])]
    public array $items = [];

    #[Groups(['api'])]
    public ?int $totalCount = null;

    public function __construct(array $items = [], ?int $totalCount = null)
    {
        $this->items = $items;
        $this->totalCount = $totalCount;
    }

}
