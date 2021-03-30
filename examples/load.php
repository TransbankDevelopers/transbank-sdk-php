<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (!function_exists('currentUrlPath')) {
    /**
     * Returns the current URL Path
     *
     * @param  string  $append
     *
     * @return string
     */
    function currentUrlPath(string $append = ''): string
    {
        // Get the request path with the script name
        $requestPath = trim($_SERVER['REQUEST_URI'], '/');

        $requestPath = explode('?', $requestPath, 2)[0];

        // Form the URL and add the path.
        $url = $_SERVER['HTTPS'] ?? false ? 'https' : 'http' .
                '://' . $_SERVER['HTTP_HOST'] . '/' . $requestPath;

        // Parse the $url
        $parsed = pathinfo($url);

        // If the URL has a filename, strip it from it to leave only the path.
        if(isset($parsed['filename'], $parsed['extension'])) {
            $url = str_replace($parsed['basename'], '', $url);
        }

        // Clean the path and return it with the appended filename (if its set)
        return trim($url, '/') . ($append ? '/' . $append : '');
    }
}
