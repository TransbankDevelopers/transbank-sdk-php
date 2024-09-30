<?php

use PHPUnit\Framework\TestCase;
use Transbank\Utils\Curl\Uri;

class UriTest extends TestCase
{
    private Uri $uri;
    private String $exampleUri = 'https://usuario:pass@www.ejemplo.com:8080/ruta/al/recurso?busqueda=phpunit&filtro=activo#seccion2';

    public function setUp(): void
    {
        $this->uri = new Uri($this->exampleUri);
    }

    /** @test */
    public function it_can_get_data()
    {
        $this->assertEquals('usuario:pass', $this->uri->getUserInfo());
        $this->assertEquals('https', $this->uri->getScheme());
        $this->assertEquals('usuario:pass@www.ejemplo.com:8080', $this->uri->getAuthority());
        $this->assertEquals(8080, $this->uri->getPort());
        $this->assertEquals('seccion2', $this->uri->getFragment());
        $this->assertEquals($this->exampleUri, $this->uri);
    }

    /** @test */
    public function it_can_set_data()
    {
        $newUri = $this->uri->withScheme('http');
        $this->assertNotSame($newUri, $this->uri);
        $this->assertEquals('http', $newUri->getScheme());
        $this->assertEquals('https', $this->uri->getScheme());

        $newUri = $this->uri->withUserInfo('newUser', 'testPassword');
        $this->assertNotSame($newUri, $this->uri);
        $this->assertEquals('newUser:testPassword', $newUri->getUserInfo());
        $this->assertEquals('usuario:pass', $this->uri->getUserInfo());

        $newUri = $this->uri->withHost('www.new-host.cl');
        $this->assertNotSame($newUri, $this->uri);
        $this->assertEquals('www.new-host.cl', $newUri->getHost());
        $this->assertEquals('www.ejemplo.com', $this->uri->getHost());

        $newUri = $this->uri->withPort(9000);
        $this->assertNotSame($newUri, $this->uri);
        $this->assertEquals(9000, $newUri->getPort());
        $this->assertEquals(8080, $this->uri->getPort());

        $newUri = $this->uri->withPath('/nueva/ruta');
        $this->assertNotSame($newUri, $this->uri);
        $this->assertEquals('/nueva/ruta', $newUri->getPath());
        $this->assertEquals('/ruta/al/recurso', $this->uri->getPath());

        $newUri = $this->uri->withQuery('nuevabusqueda=test&filtro=inactivo');
        $this->assertNotSame($newUri, $this->uri);
        $this->assertEquals('nuevabusqueda=test&filtro=inactivo', $newUri->getQuery());
        $this->assertEquals('busqueda=phpunit&filtro=activo', $this->uri->getQuery());

        $newUri = $this->uri->withFragment('newfragment');
        $this->assertNotSame($newUri, $this->uri);
        $this->assertEquals('newfragment', $newUri->getFragment());
        $this->assertEquals('seccion2', $this->uri->getFragment());

        $this->expectException(InvalidArgumentException::class);
        $newUri = new Uri('http://:80');
    }
}
