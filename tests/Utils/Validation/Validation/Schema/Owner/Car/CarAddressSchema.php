<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Owner\Car;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\Common\Country\CountrySchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class CarAddressSchema implements ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'country' => CountrySchema::bo_view(),
                'streetAddress' => 'string',
                'city' => 'string',
                'latitude' => 'string',
                'longitude' => 'string',
                'parish' => ParishMasterSchema::bo_view(),
                'zipPostalCode' => 'string',
                'id' => 'string',
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'car'=> CarSchema::bo_view(),
            'country' => CountrySchema::bo_view(),
            'streetAddress' => 'string',
            'city' => 'string',
            'latitude' => 'string',
            'longitude' => 'string',
            'parish' => ParishMasterSchema::bo_view(),
            'zipPostalCode' => 'string',
            'id' => 'string',
        ]);
    }
}