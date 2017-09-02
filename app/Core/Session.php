<?php

namespace App\Core;


/**
 *
 * TODO: implement encrypt/decrypt cookie
 */
class Session
{
    /**
     * Sart session
     */
    public static function start()
    {
        session_start();
    }

    /**
     * Destroy session
     *
     */
    public static function destroy()
    {
        $_SESSION = [];
        session_destroy();
    }


    /**
     * Get session by key
     *
     * @param string $key
     *
     * @return mix
     */
    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Set session
     *
     * @param string $key
     * @param mix $value
     *
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
}
