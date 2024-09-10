<?php

namespace App\Controllers;



// auto loaders no require
use Framework\Database;
use Framework\Validation;
use Framework\Session;

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
        }
        // no need for else as the exit will not let code run past if there is an error

        // Check if account exists
        $params = [
            'email' => $email,
        ];
        // we use prepared statements as this is a user input

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user) {
            // if user then add to errors array
            $errors['email'] = 'That email already exists';
            loadView('users/create', [
                'errors' => $errors,
            ]);
            exit;
        }

        // Create account
        // using prepared statements with named paramaters
        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            // this uses bcrypt under the hood
            // different options to pass in 
            // PASSWORD_DEFAULT is bcrypt
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $params);

        // Get the user id - on the conn property
        $userId = $this->db->conn->lastInsertId();

        // inspectAndDie([
        //     'id' => $userId,
        //     'name' => $name,
        //     'email' => $email,
        //     'city' => $city,
        //     'state' => $state,
        // ]);

        // user is key - can be just id or anything we want
        // name and email instead of making a req each time 
        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
        ]);
        // 
        // inspectAndDie(Session::get('user'));
        redirect('/');
        // inspectAndDie('success!');
    }


    /**
     * Logout the user
     *
     * @return void
     */
    public function logout()
    {
        // 
        Session::clearAll(); // destroy the session 

        /**
         * session_get_cookie_params(); returns the path and domain the cookie belongs to
         * we cant just call clear cookie we need to set it 
         * PHPSESSID find by looking in the console cookies
         * pass in '' for value and then set time earlier to NOW
         * 
         */

        //  PARAMAS RETURNS AN ARRAY
        $params = session_get_cookie_params();
        // inspect($params);
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
    }
}
