<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Owner\RentalCar;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class RentalCarSchema implements ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'car' => 'object',
                'pickupAddress' => 'string',
                'fromDateTime' => 'datetime',
                'toDateTime' => 'datetime',
                'status' => 'string',
                'customer' => 'object',
                'isPaymentDone' => 'boolean',
                'id' => 'string',
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'car' => 'object',
            'pickupAddress' => 'string',
            'fromDateTime' => 'datetime',
            'toDateTime' => 'datetime',
            'status' => 'string',
            'rentalCarPayments' => 'array|null',
            'cancelRentalCarRequests' => 'array|null',
            'customer' => 'object|null',
            'isPaymentDone' => 'boolean',
            'id' => 'string'
        ]);
    }

}
