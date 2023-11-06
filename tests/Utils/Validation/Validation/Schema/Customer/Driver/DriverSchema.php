<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Customer\Driver;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class DriverSchema implements ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'id' => 'string',
                'firstName' => 'string',
                'lastName' => 'string',
                'middleName' => 'string',
                'licenseNumber' => 'string',
                'dateOfBirth' => 'string',
                'expirationDate' => 'string',
                'isApproved' => 'boolean',
                'country' => 'object',
                'parish' => 'object'
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'id' => 'string',
            'firstName' => 'string',
            'lastName' => 'string',
            'middleName' => 'string',
            'licenseNumber' => 'string',
            'dateOfBirth' => 'string',
            'expirationDate' => 'string',
            'isApproved' => 'boolean',
            'country' => 'object',
            'parish' => 'object',
        ]);
    }

}
