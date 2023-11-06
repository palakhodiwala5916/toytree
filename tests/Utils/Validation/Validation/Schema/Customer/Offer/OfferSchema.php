<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Customer\Offer;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class OfferSchema implements ObjectSchemaInterface
{

    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'id' => 'string',
                'name' => 'string',
                'promoCode' => 'string',
                'type' => 'string',
                'value' => 'number',
                'validUptoCustomer' => 'number',
                'fromDate' => 'date',
                'toDate' => 'date',
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        // TODO: Implement bo_view() method.
    }
}
