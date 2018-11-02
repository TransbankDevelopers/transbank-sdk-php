<?php

namespace Transbank\Helpers;

class Helpers
{
    /**
     * Returns the class name without the namespace
     *
     * @param string $class
     * @return mixed
     */
    public static function class_basename($class)
    {
        return array_pop(explode('\\', __CLASS__));
    }

}
