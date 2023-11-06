<?php

namespace App\VO\Protocol\Common;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class FilterSortByField
{
    /**
     * @Serializer\Type("string")
     * @Assert\NotNull()
     */
    public string $key;

    /**
     * @Serializer\Type("string")
     * @Assert\Choice({"ASC", "DESC"})
     */
    public string $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

}
