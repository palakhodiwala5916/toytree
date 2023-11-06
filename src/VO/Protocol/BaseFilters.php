<?php

namespace App\VO\Protocol;

use App\VO\Protocol\Common\FilterSortByField;

class BaseFilters implements RequestQueryInterface
{
    public ?string $limit = null;

    public ?string $offset = '0';

    /**
     * @var FilterSortByField[]|array
     */
    public array $sortBy = [];
}
