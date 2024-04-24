<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Exceptions\TransbankException;

class TransbankExceptionTest extends TestCase
{
    public function testConstructor()
    {
        // Arrange
        $message = 'Test message';
        $code = 0;
        $previous = null;

        // Act
        $exception = new TransbankException($message, $code, $previous);

        // Assert
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertEquals($previous, $exception->getPrevious());
    }

    public function testDefaultMessage()
    {
        // Arrange
        $defaultMessage = TransbankException::DEFAULT_MESSAGE;
        $code = 0;
        $previous = null;

        // Act
        $exception = new TransbankException(null, $code, $previous);

        // Assert
        $this->assertEquals($defaultMessage, $exception->getMessage());
    }
}
