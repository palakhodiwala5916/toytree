<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Common\Language;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class LanguageSchema implements ObjectSchemaInterface
{

    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'id' => 'string',
                'code' => 'string',
                'name' => 'string',
                'isActive' => 'boolean',
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        // TODO: Implement bo_view() method.
    }
}
