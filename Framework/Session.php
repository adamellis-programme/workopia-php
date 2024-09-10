<?php

namespace Framework;

class Session
{

    /**
     * Start the session
     *
     * @return void
     * 
     * 0 = session disabled 
     * 1= no session
     * 3= session started

     */
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session key/value pair
     *
     * @param string $keyX
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */

    public static function get($key, $default = null)
    {
        // ? IF SET RETURN : NULL
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Check if a session key exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Clear a session key
     *
     * @param string $key
     * @return void
     */
    public static function clear($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear all session data
     *
     * @return void
     */
    public static function clearAll()
    {
        session_unset();
        session_destroy();
    }
}
