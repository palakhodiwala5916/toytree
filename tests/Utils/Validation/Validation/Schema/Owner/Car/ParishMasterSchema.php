<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Owner\Car;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class ParishMasterSchema implements ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'id' => 'string',
                'name' => 'string',
                'isActive' => 'boolean',
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'id' => 'string',
            'name' => 'string',
            'isActive' => 'boolean',
        ]);
    }
}