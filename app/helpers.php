<?php

/**
 * Returns an environment variable value, or the default
 * @param $var_name
 */
function env($var_name, $default = null)
{
    $value = getenv($var_name);

    if ($value !== false) {
        return $value;
    }

    return $default;
}