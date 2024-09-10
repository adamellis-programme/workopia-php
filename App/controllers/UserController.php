<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;


namespace App\Controllers;

// auto loaders no require
use Framework\Database;
use Framework\Validation;

class UserController
{

    protected $db;

    // auto runs
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the login page
     *
     * @return void
     */
    public function login()
    {
        loadView('users/login', [
            'errors' => $errors ?? [],
        ]);
    }

    /**
     * Show the registration page
     *
     * @return void
     */
    public function create()
    {
        loadView('users/create', [
            'errors' => $errors ?? [],
        ]);
    }


    /**
     * Store a new user
     *
     * @return void
     */
    public function store()
    {
        // FOR VALIDATION
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation']; // NAME ATRTRIBUTES

        $errors = [];

        // Validate email
        // ! NOT VALID 
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if (!Validation::string($name, 1, 50)) {
            $errors['name'] = 'Please enter a valid name';
        }

        // Validate password length
        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters long';
        }

        // Validate password match IF A === B
        if (!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }


        // IF ERRORS LOAD VIEW WITH THE ERORORS
        if (!empty($errors)) {
            loadView('users/create', [
                'errors' => $errors,
                // HERE WE MAKE OUR OWN ARRAY TO SEND
                // -- go to view and show errors: users/create
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ]
            ]);
            exit;
        } else {
            inspectAndDie('success!');
        }
    }
}
