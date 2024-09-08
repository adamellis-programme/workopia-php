<?php

namespace Framework;

class Validation
{
    /**
     * Validate a string
     * @param string $value
     * @param int $min
     * @param int $max
     * @return bool
     */

    //  static not instatiate, INF infinity
    public static function string($value, $min = 1, $max = INF)
    {
        if (is_string($value)) {
            $value = trim($value); // white space trimmed
            $length = strlen($value);
            // if true return 
            return $length >= $min && $length <= $max;
        }

        // else return false 
        return false;
    }


    /**
     * Validate an email address
     * @param string $value
     * @return mixed
     */
    // DATA sanitization and validation
    public static function email($value)
    {
        $value = trim($value);

        // filter_var takes in string and use the email filter
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }


    /**
     * Match a value against another value
     * @param string $value
     * @return bool
     */
    public static function match($value1, $value2)
    {
        $value1 = trim($value1);
        $value2 = trim($value2);

        return $value1 === $value2;
    }
}
