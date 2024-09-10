<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Exceptions\TransbankException;

class TransbankExceptionTest extends TestCase
{
    /** @test */
    public function it_can_instantiate_transbank_exception_with_default_values()
    {
        $exception = new TransbankException();

        $this->assertEquals('An error has happened, verify given parameters and try again.', $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    /** @test */
    public function test_it_can_set_custom_message_and_code()
    {
        $customMessage = 'Custom error message';
        $customCode = 123;

        $exception = new TransbankException($customMessage, $customCode);

        $this->assertEquals($customMessage, $exception->getMessage());

        $this->assertEquals($customCode, $exception->getCode());
    }

    /** @test */
    public function test_it_can_set_previous_exception()
    {
        $previousException = new \Exception('Previous exception');

        $exception = new TransbankException('Custom message', 0, $previousException);

        $this->assertSame($previousException, $exception->getPrevious());
    }
}
