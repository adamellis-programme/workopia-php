<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

// we do not have to reqire this Class anywhere as it is 
// being autoloaded into any file that needs it 
class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /*
   * Show all listings
   *
   * @return void
   */
    public function index()
    {
        // scope resalution operator
        // inspectAndDie(Validation::match('hello',  'hello'));
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();

        loadView('listings/index', [
            'listings' => $listings
        ]);
    }

    public function create()
    {
        loadView('listings/create');
    }
    public function show($params)
    {

        // inspect($params);
        // $id = $_GET['id'] ?? '';
        $id = $params['id'] ?? '';
        // inspect($id);


        $params = [
            'id' => $id,
        ];
        // Use prepared statements and never this way 
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // check if listing exists

        if (!$listing) {
            // -: Because it's a static method, you 
            // -: can call it directly on the class 
            // -: itself without creating an instance of 
            // -: the ErrorController class. In other words, 
            // -: the method belongs to the class, not to a 
            // -: specific object of that class.
            // -: :: This is the scope resolution operator, 
            // -: This is a common practice for utility methods or methods that don't require an object state to function, such as error handling in this case.
            ErrorController::notFound('Listing not found');
            return;
        }
        // inspect($listing);
        loadView('listings/show', [
            'listing' => $listing,
        ]);
    }



    // store the data / POST
    // Store data in database 
    // $_POST is how we get the data from the form
    public function store()
    {
        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        // Filter the POST data to include only allowed fields
        /**
         * array_intersect_key: returns an new arr as long as the key is in both arrays
         * array_flip as no keys in allow we need to use flip
         */
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $newListingData['user_id'] = 1;

        // Sanitize the data - 
        // 1st call functin to run callback on 
        // 2nd enter the array of data that the fucntion needs to do work on
        $newListingData = array_map('sanitize', $newListingData);


        // Validate required fields
        $requiredFields = ['title', 'description', 'email', 'city', 'state'];

        // to be displayed in the ui
        // uc first is first letter uppercase
        $errors = [];
        foreach ($requiredFields as $field) {
            // inspect($newListingData[$field]);
            // validation / string - has to be a least 1 char as default
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        // inspectAndDie($errors);
        if (!empty($errors)) {
            // Reload view with errors - go to view and make sure it can recieve errors
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData,
            ]);
            exit;
        } else {
            echo 'Success';
            // All required fields are present and validated
            // Insert data into the database, including non-required fields
            // ...
        }
    }
}
