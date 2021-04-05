<?php

namespace Transbank\Contracts;

interface HttpClientInterface
{
    public function request($method, $url, $payload = [], $options = null);
}
