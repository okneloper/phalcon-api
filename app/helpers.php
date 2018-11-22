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

function d($var, $varName = null)
{
    static $callCounter = 0;

    $trace = debug_backtrace();
    $traceIndex = isset($trace[1]) && $trace[1]['function'] == 'dd' ? 1 : 0;

    $invisible = is_null($var) || is_bool($var) || $var === '';

    if (isset($trace[$traceIndex + 1])) {
        $func = $trace[$traceIndex + 1]['function'];
    } else {
        $func = '{main}';
    }
    $file = $trace[$traceIndex]['file'];
    if (defined('ROOT')) {
        $file = preg_replace('#^'. realpath(ROOT) .'#', '', $file);
    }

    $callNr = sprintf("%5d", ++$callCounter);
    $traceHelperString = "\n{$trace[$traceIndex]['line']} |$callNr {$file} on line {$trace[$traceIndex]['line']} {$func}(): {$trace[$traceIndex]['function']}()\n";

    if (php_sapi_name() == 'cli') {
        if ($invisible) {
            var_dump($var);
            echo PHP_EOL;
        } else {
            echo print_r($var, true) . PHP_EOL;
        }
        echo $traceHelperString;
    } else {
        if ($varName) {
            echo "<div style=\"background: #f00; color: #fff;\">\n$varName\n</div>\n";
        }
        if ($invisible) {
            echo "<pre style=\"background: #fff; color: #333\">\n"; var_dump($var); echo "\n</pre>\n";
        } else {
            echo "<pre style=\"background: #fff; color: #333\">\n" . print_r($var, true) . "\n</pre>\n";
        }

        echo "<div style='padding: 20px; border: 1px solid #f00;'>$traceHelperString</div>" . PHP_EOL . PHP_EOL;
    }
}

/**
 * Dump & Die
 * @param mixed $var
 */
function dd($var, $varName = null)
{
    if (!empty($GLOBALS['dd_disable'])) { return; }
    d($var, $varName);
    die();
}