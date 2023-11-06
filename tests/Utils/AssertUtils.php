<?php

namespace App\Tests\Utils;

use App\Tests\Utils\Validation\ObjectSchema;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class AssertUtils
{
    public static function assertSuccessResponse(ResponseInterface $response): void
    {
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode(), 'Invalid status code returned, expecting 200, got: ' . $response->getStatusCode());
    }

    public static function assertJsonResponse(ResponseInterface $response): void
    {
        Assert::assertEquals('application/json', $response->getHeader('Content-Type')[0], 'Content-type is not application/json');
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public static function decodeSuccessResponse(ResponseInterface $response): mixed
    {
        AssertUtils::assertSuccessResponse($response);
        AssertUtils::assertJsonResponse($response);
        $ret = json_decode($response->getBody()->getContents());
        Assert::assertEquals(JSON_ERROR_NONE, json_last_error(), 'Error in the response JSON detected');
        Assert::assertObjectHasAttribute('success', $ret, 'Property `success` not found in response body');
        Assert::assertObjectHasAttribute('data', $ret, 'Property `data` not found in response body');

        if ($ret->success !== true) {
            Assert::assertObjectHasAttribute('error', $ret->data, 'Property `error` not found in response body data');
            AssertUtils::verifyObjectSchema($ret->data, ObjectSchema::fromArray([
                'message' => 'string',
                'code'    => 'integer|null'
            ]));
            Assert::fail($ret->data->error->message);
        }

        return $ret->data;
    }

    public static function expectErrorResponse(ResponseInterface $response, int $expectedStatus = null, string $messageKey = null, string $expectedMessage = null)
    {
        if ($expectedStatus) {
            Assert::assertEquals($expectedStatus, $response->getStatusCode(), 'Expecting error status ' . $expectedStatus . ', got: ' . $response->getStatusCode());
        } else {
            Assert::assertNotEquals(Response::HTTP_OK, $response->getStatusCode(), 'Expecting error status, got: ' . $response->getStatusCode());
        }
        Assert::assertEquals('application/json', $response->getHeader('Content-Type')[0], 'Content-type is not application/json');
        $ret = json_decode($response->getBody()->getContents());
        Assert::assertEquals(JSON_ERROR_NONE, json_last_error(), 'Error in the response JSON detected');

        if ($messageKey && $expectedMessage) {
            Assert::assertObjectHasAttribute($messageKey, $ret, "No `$messageKey` key found in response");
            Assert::assertEquals($expectedMessage, $ret->{$messageKey}, sprintf('Expected error message: `%s`, got: `%s`', $expectedMessage, $ret->message));
        }

        return $ret;
    }

    public static function verifyObjectSchema(object $object, ObjectSchema|array $schema): void
    {
        if (is_array($schema)) {
            $schema = ObjectSchema::fromArray($schema);
        }

        foreach ($schema->getFields() as $field) {
            if (!$field->isRequired) {
                continue;
            }

            if ($field->type instanceof ObjectSchema) {
                if ($field->type->isArray()) {
                    if (count($object->{$field->name}) > 0) {
                        AssertUtils::assertObjectHasFieldType($object, $field->name, 'array');
                        AssertUtils::verifyObjectSchema($object->{$field->name}[0], $field->type);
                    }
                } else {
                    AssertUtils::assertObjectHasFieldType($object, $field->name, 'object');
                    AssertUtils::verifyObjectSchema($object->{$field->name}, $field->type);
                }
            } else {
                AssertUtils::assertObjectHasFieldType($object, $field->name, $field->type);
            }
        }
    }

    public static function assertObjectHasFieldType(object $object, string $field, string $type): void
    {
        Assert::assertObjectHasAttribute($field, $object, sprintf("Object doesn't contain '%s' key.", $field));

        if (strpos($type, '|') !== false) {
            $fails = 0;
            $parts = explode('|', $type);
            foreach ($parts as $partialType) {
                try {
                    self::assertFieldType($object, $field, $partialType);
                } catch (ExpectationFailedException $e) {
                    $fails++;
                }
            }

            if ($fails === count($parts)) {
                Assert::fail(sprintf("Object '%s' parameter with value '%s' is not '%s'", $field, $object->{$field}, $type));
            }
        } else {
            self::assertFieldType($object, $field, $type);
        }
    }

    public static function assertDateFormat(string $format, string $date): void
    {
        $isValid = strtotime($date) && date($format, strtotime($date)) === $date;
        Assert::assertTrue($isValid, sprintf("Date `%s` does not have format `%s`", $date, $format));
    }

    private static function assertFieldType(object $object, string $field, string $type): void
    {
        $type = trim($type);
        switch ($type) {
            case 'string':
                Assert::assertIsString($object->{$field}, sprintf("Object '%s' parameter with value '%s' is not '%s'", $field, $object->{$field}, $type));
                break;
            case 'DateTime':
            case "DateTime<'Y-m-d H:i:s'>":
            case "Y-m-d H:i:s":
                Assert::assertIsString($object->{$field}, sprintf("Object '%s' parameter with value '%s' is not '%s'", $field, $object->{$field}, $type));
                AssertUtils::assertDateFormat('Y-m-d H:i:s', $object->{$field});
                break;
            case \DateTimeInterface::RFC3339:
                Assert::assertIsString($object->{$field}, sprintf("Object '%s' parameter with value '%s' is not '%s'", $field, $object->{$field}, $type));
                AssertUtils::assertDateFormat(\DateTimeInterface::RFC3339, $object->{$field});
                break;
            case 'int':
            case 'integer':
            case "DateTime<'U'>":
                Assert::assertIsInt($object->{$field}, sprintf("Object '%s' parameter with value '%s' is not '%s'", $field, $object->{$field}, $type));
                break;
            case 'double':
            case 'float':
                Assert::assertIsFloat($object->{$field}, sprintf("Object '%s' parameter with value '%s' is not '%s'", $field, $object->{$field}, $type));
                break;
            case 'array':
                if (gettype($object->{$field}) != 'array') {
                    Assert::assertIsObject($object->{$field}, sprintf("Object '%s' parameter is not '%s'", $field, $type));
                }
                break;
            case 'boolean':
                Assert::assertIsBool($object->{$field}, sprintf("Object '%s' parameter with value '%s' is not '%s'", $field, $object->{$field}, $type));
                break;
            case 'object':
                Assert::assertIsObject($object->{$field}, sprintf("Object '%s' parameter is not '%s'", $field, $type));
                break;
            case 'media':
            case 'App\Application\Sonata\MediaBundle\Entity\Media':
                AssertUtils::assertObjectHasFieldType($object->{$field}, 'url', 'object');
                AssertUtils::assertObjectHasFieldType($object->{$field}->url, 'original', 'string');
                AssertUtils::assertObjectHasFieldType($object->{$field}->url, 'small', 'string');
                AssertUtils::assertObjectHasFieldType($object->{$field}->url, 'preview', 'string');
                break;
            case 'null':
                Assert::assertNull($object->{$field}, sprintf("Object '%s' parameter is not '%s'", $field, $type));
                break;
        }
    }
}
