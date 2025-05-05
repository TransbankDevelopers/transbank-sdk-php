<?php

/**
 * Class Utils.
 *
 * @category
 */

namespace Transbank\Utils;

class Utils
{
    /**
     * @param array $json
     * @param string $key
     * @return mixed
     */
    public static function returnValueIfExists(array $json, string $key): mixed
    {
        return isset($json[$key]) ? $json[$key] : null;
    }
}
