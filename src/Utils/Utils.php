<?php

/**
 * Class Utils.
 *
 * @category
 */

namespace Transbank\Utils;

class Utils
{
    public static function returnValueIfExists($json, $key)
    {
        return isset($json[$key]) ? $json[$key] : null;
    }
}
