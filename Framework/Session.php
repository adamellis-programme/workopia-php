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



    /**
     * Set a flash message
     *
     * @param string $key
     * @param string $message
     * @return void
     */
    public static function setFlashMessage($key, $message)
    {
        // we are calling self: the set method above
        // self as we are using static
        // key is flash_thisMessage
        // value is message
        // flash_success
        self::set('flash_' . $key, $message);
    }



    /**
     * Get a flash message and unset right away
     * 
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * 
     * if it does not exist then it will be null
     * default is the VALUE and we give it a default of null
     * 
     * getFlashMessage used in PARTIALS to get message
     * 
     * 
     */
    public static function getFlashMessage($key, $default = null)
    {
        
        $message = self::get('flash_' . $key, $default);
        self::clear('flash_' . $key); // Clear the flash message after retrieval
        return $message;
    }
}


// using methods we have already created
