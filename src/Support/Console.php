<?php

namespace Heller\LaravelUpdater\Support;

require_once __DIR__.'/IO.php';

class Console
{
    public static function log($string)
    {
        echo $string.PHP_EOL;
    }

    public static function info($string)
    {
        echo self::colorize($string, 'blue').PHP_EOL;
    }

    public static function success($string)
    {
        echo self::colorize($string, 'green').PHP_EOL;
    }

    public static function warning($string)
    {
        echo self::colorize($string, 'yellow').PHP_EOL;
    }

    public static function error($string)
    {
        echo self::colorize($string, 'red').PHP_EOL;
    }

    protected static function colorize($text, $color)
    {
        return colorize($text, $color);
    }
}
