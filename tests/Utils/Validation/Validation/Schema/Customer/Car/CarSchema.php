<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Customer\Car;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class CarSchema implements ObjectSchemaInterface
{

    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'id' => 'string',
                'name' => 'string',
                'carType' => 'object|null',
                'carBrand' => 'object',
                'model' => 'string',
                'numberPlate' => 'string',
                'transmission' => 'string',
                'carFeature' => 'array',
                'carInformation' => 'array',
                'carAssets' => 'array',
                'insuranceProtection' => 'object',
                'isFavourite' => 'boolean'
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'id' => 'string',
            'name' => 'string',
            'description' => 'string',
            'isActive' => 'boolean',
            'carOwner' => 'object',
            'carFeature' => 'array',
            'carInformation' => 'array',
            'insuranceProtection' => 'object',
            'reviews' => 'array',
            'parish' => 'object'
        ]);
    }

    public static function bo_review_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => 'array',
            'rating' => 'object'
        ]);
    }

    public static function bo_feature_list()
    {
        return [
            [
                'id' => 'string',
                'name' => 'string',
                'icon' => 'string|null',
                'description' => 'string',
                'isActive' => 'boolean'
            ]
        ];
    }

}
