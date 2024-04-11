<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassComercio\Responses\InscriptionFinishResponse;

class InscriptionFinishResponseTest extends TestCase
{
    /** @test */
    public function it_sets_status_to_ok_when_http_code_is_204()
    {
        $response = new InscriptionFinishResponse(204);
        $this->assertSame('OK', $response->status);
        $this->assertSame(204, $response->code);
    }

    /** @test */
    public function it_sets_status_to_not_found_when_http_code_is_404()
    {
        $response = new InscriptionFinishResponse(404);
        $this->assertSame('Not Found', $response->status);
        $this->assertSame(404, $response->code);
    }

    /** @test */
    public function it_sets_status_to_null_when_http_code_is_not_204_or_404()
    {
        $response = new InscriptionFinishResponse(500);
        $this->assertNull($response->status);
        $this->assertSame(500, $response->code);
    }

    /** @test */
    public function it_can_set_and_get_status()
    {
        $response = new InscriptionFinishResponse(200);
        $response->setStatus('OK');
        $this->assertSame('OK', $response->getStatus());
    }

    /** @test */
    public function it_can_set_and_get_code()
    {
        $response = new InscriptionFinishResponse(204);
        $response->setCode(204);
        $this->assertSame(204, $response->getCode());
    }
}
