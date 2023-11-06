<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Owner\Car;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class CarSchema implements ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'carBrand' => CarBrandSchema::bo_view().'|null',
                'model' => 'string|null',
                'carAssets' => 'object|array',
                'isCompleted' => 'boolean',
                'id' => 'string',
                'name' => 'string',
                'description' => 'string',
                'isActive' => 'boolean',
                'isFavourite' => 'boolean'
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'carType' => CarTypeSchema::bo_view().'|null',
            'carBrand' => CarBrandSchema::bo_view().'|null',
            'make' => 'string|null',
            'year' => 'string|null',
            'model' => 'string|null',
            'numberPlate' => 'string|null',
            'transmission' => 'string',
            'VINNumber' => 'string|null',
            'marketValue' => 'string',
            'isModelYear1980Older' => 'boolean',
            'carFeature' => CarFeatureSchema::bo_view().'|null',
            'carInformation' => CarInformationSchema::bo_view().'|null',
            'carAssets' => 'object|array',
            'insuranceProtection' => 'object|null',
            'reviews' => 'object|array',
            'carAddress' => CarAddressSchema::bo_view().'|null',
            'isCompleted' => 'boolean',
            'id' => 'string',
            'name' => 'string',
            'description' => 'string',
            'isActive' => 'boolean',
            'isFavourite' => 'boolean'
        ]);
    }
}