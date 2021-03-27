<?php

namespace Tests;

trait FormatsToCamelCase
{
    protected static function snakeCaseToPascalCase(string $string): string
    {
        return str_replace('_', '', ucwords($string, '_'));
    }
}