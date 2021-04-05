<?php

namespace Transbank\Utils;

use Transbank\Webpay\Options;

abstract class EnvironmentManager
{
    use ConfiguresEnvironment;

    protected static $globalOptions = null;
    const DEFAULT_API_KEY = Options::DEFAULT_API_KEY;
}
