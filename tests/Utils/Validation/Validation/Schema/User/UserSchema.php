<?php

namespace App\Tests\Utils\Validation\Validation\Schema\User;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class UserSchema implements ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema
    {
        // TODO: Implement bo_list() method.
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'verifiedInfo' => [
                'id' => 'string',
                'fullName' => 'string',
                'email' => 'string',
                'phoneNumber' => 'number',
                'role' => 'number',
                'isEmailVerified' => 'boolean',
                'isPhoneVerified' => 'boolean',
                'profilePicture' => 'string|null',
                'aboutYourself' => 'string|null',
                'torontoOnCanada' => 'string|null',
                'city' => 'string|null',
                'toronto' => 'string|null',
                'english' => 'string|null',
                'joinDate' => 'string|null',
            ],
            'rating' => 'array'
        ]);
    }
}