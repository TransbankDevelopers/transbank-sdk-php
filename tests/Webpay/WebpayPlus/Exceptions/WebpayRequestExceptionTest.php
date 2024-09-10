<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Utils\TransbankApiRequest;

class WebpayRequestExceptionTest extends TestCase
{
    public function testConstructor()
    {
        $message = 'Test message';
        $tbkErrorMessage = 'TBK Error message';
        $httpCode = 400;
        $failedRequest = $this->createMock(TransbankApiRequest::class);

        $exception = new WebpayRequestException($message, $tbkErrorMessage, $httpCode, $failedRequest);

        $this->assertEquals($tbkErrorMessage, $exception->getTransbankErrorMessage());
        $this->assertEquals($httpCode, $exception->getHttpCode());
        $this->assertEquals($failedRequest, $exception->getFailedRequest());
    }

    public function testRaise()
    {
        $message = 'Test message';
        $tbkErrorMessage = 'TBK Error message';
        $httpCode = 400;
        $failedRequest = $this->createMock(TransbankApiRequest::class);

        $exception = new WebpayRequestException($message, $tbkErrorMessage, $httpCode, $failedRequest);
        $raisedException = WebpayRequestException::raise($exception);

        $this->assertEquals($exception->getMessage(), $raisedException->getMessage());
        $this->assertEquals($exception->getTransbankErrorMessage(), $raisedException->getTransbankErrorMessage());
        $this->assertEquals($exception->getHttpCode(), $raisedException->getHttpCode());
        $this->assertEquals($exception->getFailedRequest(), $raisedException->getFailedRequest());
    }

    public function testGetExceptionMessage()
    {
        $errorMessage = 'error message';
        $failedRequest = $this->createMock(TransbankApiRequest::class);
        $exception = new WebpayRequestException($errorMessage, null, 404, $failedRequest);

        $this->assertEquals($errorMessage, $exception->getMessage());
    }
}
