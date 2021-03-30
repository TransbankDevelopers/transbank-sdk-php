<?php

namespace Tests;

use Transbank\Sdk\ApiRequest;
use PHPUnit\Framework\TestCase;

class ApiRequestTest extends TestCase
{
    public function test_serializes_to_json(): void
    {
        $request = new ApiRequest('foo', [
            'foo' => 'bar'
        ]);

        static::assertEquals('{"foo":"bar"}', $request->toJson());
    }

    public function test_array_access(): void
    {
        $request = new ApiRequest('foo', [
            'foo' => 'bar'
        ]);

        static::assertEquals('bar', $request['foo']);
        static::assertTrue(isset($request['foo']));
        static::assertFalse(isset($request['bar']));

        $request['baz'] = 'cougar';

        static::assertEquals('cougar', $request['baz']);

        unset($request['baz']);

        static::assertFalse(isset($request['baz']));
    }

    public function test_exception_on_non_existent_key(): void
    {
        $this->expectError();
        $this->expectErrorMessage('Undefined index: bar');

        $request = new ApiRequest('foo', [
            'foo' => 'bar'
        ]);

        $request['bar'];
    }
}
