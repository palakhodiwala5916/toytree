<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Customer\Contact;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class CustomerContactSchema implements ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'id' => 'string',
                'contactCustomer' => 'object',
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        // TODO: Implement bo_view() method.
    }
}
