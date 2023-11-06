<?php

namespace App\Tests\Utils\Validation\Validation\Schema;

use App\Tests\Utils\Validation\ObjectSchema;

class SettingSchema implements ObjectSchemaInterface
{

    public static function bo_list(): ObjectSchema
    {
        // TODO: Implement bo_list() method.
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'id' => 'string',
            'termsConditions' => 'string',
            'confidentialityPolicy' => 'string',
            'aboutUs' => 'string',
            'contactNumber'=> 'number',
            'headOfficeAddress'=> 'string',
            'headOfficeLatitude'=> 'string',
            'headOfficeLongitude'=> 'string',
        ]);
    }
}
