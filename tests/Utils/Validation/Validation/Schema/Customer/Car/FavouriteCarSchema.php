<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Customer\Car;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class FavouriteCarSchema implements ObjectSchemaInterface
{

    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'car' => ObjectSchema::fromArray([
                    'id' => 'string',
                    'name' => 'string',
                    'carType' => 'object',
                    'carBrand' => 'object',
                    'carOwner' => 'object',
                    'model' => 'string',
                    'numberPlate' => 'string',
                    'color' => 'string',
                    'odometer' => 'string',
                    'transmission' => 'string',
                    'trim' => 'string',
                    'style' => 'string',
                    'isBrandedOrSalvageTitle' => 'boolean',
                    'carFeature' => 'array|null',
                    'carInformation' => 'array|null',
                    'carAssets' => 'array|null',
                    'reviews' => 'array|null',
                    'ride' => 'array|null',
                    'isFavourite' => 'boolean'
                ],true ),
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        // TODO: Implement bo_view() method.
    }
}