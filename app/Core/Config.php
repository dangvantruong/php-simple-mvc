<?php

namespace App\Core;

/**
 * Simple configuration class
 *
 * get('app.APP_KEY')
 *
 */
class Config
{
    private static $config;

    public static function get($key)
    {
        $pathInfo = explode('.', $key);
        if (empty($pathInfo)) {
            return null;
        }

        $configFile = sprintf("%sConfig/%s.php", APP_PATH, $pathInfo[0]);
        static::$config = require_once($configFile);

        if (count($pathInfo) > 2) {
            return static::$config[$pathInfo[1]][$pathInfo[2]];
        }

        return static::$config;
    }
}
