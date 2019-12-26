<?php

/**
 * Class Utils
 *
 * @category
 * @package Transbank\Utils
 *
 */


namespace Transbank\Utils;


class Utils
{
    public static function returnValueIfExists($json, $key)
    {
        return isset($json[$key]) ? $json[$key] : null;
    }

}
