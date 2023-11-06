<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Customer\Review;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class OwnerReviewSchema implements ObjectSchemaInterface
{

    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'id' => 'string',
                'reviewText' => 'string',
                'rating' => 'float',
                'createdDate' => 'string',
                'customer' => 'object'
            ], true),
            'totalCount' => 'number',
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        // TODO: Implement bo_view() method.
    }
}