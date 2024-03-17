<?php

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     */
    function env(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $_ENV) ? $_ENV[$key] : $default;
    }
}
