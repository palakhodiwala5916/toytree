<?php

namespace App\VO\Protocol;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class SearchFilters implements RequestQueryInterface
{
    #[Serializer\Type('string')]
    #[Assert\NotBlank(message: 'search_filter.request.text_value.required')]
    public string $textValue;

    #[Serializer\Type('string')]
    #[Assert\NotBlank(message: 'search_filter.request.target_property.required')]
    public string $targetProperty;

}
