<?php

namespace Transbank\Utils;

use Transbank\Webpay\Options;

class EnvironmentManager
{
    protected static $globalOptions = null;

    use ConfiguresEnvironment;
    const DEFAULT_API_KEY = Options::DEFAULT_API_KEY;
}
