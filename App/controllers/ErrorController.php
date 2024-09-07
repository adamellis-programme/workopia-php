<?php

namespace App\Controllers;

// specific methhod for differect types of errors 
// STATIC AS WE DO NOT NEED TO CREATE MORE THAN ONE INSTANCE
// STATIC as we do not need to instantiate a new Error Object

class ErrorController
{
    /*
     * 404 not found error
     *
     * @param string $message
     * @return void
     */
    public static function notFound($message = 'Resource Not Found!')

    {

        http_response_code(404);
        loadView('error', [
            'status' => '404',
            'message' => $message,
        ]);
    }


    /*
     * 403 unauthorized error
     *
     * @param string $message
     * @return void
     */
    public static function unauthorized($message = 'You are unauthorized to access this resource')
    {
        http_response_code('403');
        loadView('error', [
            'status' => '403',
            'message' => $message,
        ]);
    }
}
