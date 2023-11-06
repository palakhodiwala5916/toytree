<?php

namespace App\Tests\Utils\Validation\Validation\Schema\Customer\Chat;

use App\Tests\Utils\Validation\ObjectSchema;
use App\Tests\Utils\Validation\Validation\Schema\ObjectSchemaInterface;

class ChatSchema implements ObjectSchemaInterface
{

    public static function bo_list(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' =>ObjectSchema::fromArray([
                 'chatId' => 'string',
                 'message' => 'string',
                 'createdAt' => 'datetime',
                 'userId' => 'string',
                 'fullName' => 'string',
                 'unReadCount' => 'number',
                 'profilePicture' => 'string|null'
            ], true),
        ]);
    }

    public static function bo_history(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'items' => ObjectSchema::fromArray([
                'message' => 'string',
                'isRead' => 'boolean',
                'sender' => 'object',
                'receiver' => 'object',
                'id' => 'string'
            ], true),
            'totalCount' => 'number'
        ]);
    }

    public static function bo_view(): ObjectSchema
    {
        return ObjectSchema::fromArray([
            'message' => 'string',
            'isRead' => 'boolean',
            'sender' => 'object',
            'receiver' => 'object',
            'id' => 'string'
        ]);
    }
}
