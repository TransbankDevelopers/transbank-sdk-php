<?php

namespace Transbank\Sdk\Services;

trait WrapsDetail
{
    /**
     * Wraps a detail array.
     *
     * @param  array  $details
     *
     * @return array[]
     */
    protected static function wrapDetails(array $details): array
    {
        // If the first item is keyed by "zero", then it's associative.
        return isset($details[0]) ? $details : [$details];
    }
}
