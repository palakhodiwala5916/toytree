<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Customer\Payment;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class PaymentSchema implements ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'id' => 'string',
                'object' => 'string',
                'address_city' => 'string|null',
                'address_country' => 'string|null',
                'address_line1' => 'string|null',
                'address_line1_check' => 'string|null',
                'address_line2' => 'string|null',
                'address_state' => 'string|null',
                'address_zip' => 'string|null',
                'address_zip_check' => 'string|null',
                'brand' => 'string',
                'country' => 'string',
                'customer' => 'string',
                'cvc_check' => 'string|null',
                'dynamic_last4' => 'string|null',
                'exp_month' => 'number',
                'exp_year' => 'number',
                'fingerprint' => 'string',
                'funding' => 'string',
                'last4' => 'string',
                'metadata' => 'object|array|null',
                'name' => 'string|null',
                'tokenization_method' => 'string|null',
                'wallet' => 'string|null'
            ], true),
            'totalCount' => 'number'
        ]);
    }
    public static function bo_view(): ObjectSchema
    {
        // TODO: Implement bo_view() method.
    }

    public static function bo_card_view()
    {
        return [
            'items' => [
                'id' => 'string',
                'object' => 'string',
                'address_city' => 'string|null',
                'address_country' => 'string|null',
                'address_line1' => 'string|null',
                'address_line1_check' => 'string|null',
                'address_line2' => 'string|null',
                'address_state' => 'string|null',
                'address_zip' => 'string|null',
                'address_zip_check' => 'string|null',
                'brand' => 'string',
                'country' => 'string',
                'customer' => 'string',
                'cvc_check' => 'string|null',
                'dynamic_last4' => 'string|null',
                'exp_month' => 'number',
                'exp_year' => 'number',
                'fingerprint' => 'string',
                'funding' => 'string',
                'last4' => 'string',
                'metadata' => 'object|array|null',
                'name' => 'string|null',
                'tokenization_method' => 'string|null',
                'wallet' => 'string|null'
            ]
        ];
    }

}
