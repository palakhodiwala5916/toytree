<?php

namespace App\Tests\Utils\Validation\Validation\Schema;

use App\Tests\Utils\Validation\ObjectSchema;

interface ObjectSchemaInterface
{
    public static function bo_list(): ObjectSchema;

    public static function bo_view(): ObjectSchema;
}
