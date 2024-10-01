<?php

use PHPUnit\Framework\TestCase;
use Transbank\Utils\Curl\Exceptions\CurlRequestException;

class CurlRequestExceptionTest extends TestCase
{
    /** @test */
    public function it_sets_default_message()
    {
        $exception = new CurlRequestException();
        $this->assertEquals(CurlRequestException::DEFAULT_MESSAGE, $exception->getMessage());
        $newException = new CurlRequestException('test Error Message', 404);
        $this->assertTrue(str_contains($newException->__toString(), 'error code 404'));
    }
}
