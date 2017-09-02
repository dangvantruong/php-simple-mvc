<?php

namespace App\Core;

/**
 * Cookie wrapper class
 *
 * TODO: implement encrypt/decrypt cookie
 *
 */
class Cookie
{
    /**
     * Set cookie
     *
     * @param string $name cookie name
     * @param string $value cookie value
     * @param int $expire expire time in seconds
     * @param string $path cookie path
     * @param string $domain
     * @param boole security
     *
     * @return bool
     */
    public static function set($name, $value, $expire = 0, $path = "/", $domain = '', $security=false)
    {
        $second = time() + $expire;
        return setcookie($name, $value, $second, $path, $domain, $security);
    }

    /**
     * Get cookie by key
     *
     * @param string $key
     *
     * @return mix
     */
    public static function get($key)
    {
        return $_COOKIE[$key] ?? null;
    }
}
