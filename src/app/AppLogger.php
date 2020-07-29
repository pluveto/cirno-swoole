<?php

namespace App;

use Hyperf\Logger\Logger;
use Hyperf\Utils\ApplicationContext;
use Psr\Log\LoggerInterface;

class AppLogger
{
    public static function get(string $name = 'app'): LoggerInterface
    {
        return ApplicationContext::getContainer()->get(\Hyperf\Logger\LoggerFactory::class)->get($name);
    }

    public static function __callStatic($name, $arguments)
    {
        self::get()->$name($arguments[0], count($arguments) > 1 ? $arguments[1] : []);
    }
}
