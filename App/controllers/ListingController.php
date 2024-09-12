<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

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
        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();

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


        $newListingData['user_id'] = Session::get('user')['id'];

        // Sanitize the data - 
        // 1st call functin to run callback on 
        // 2nd enter the array of data that the fucntion needs to do work on
        $newListingData = array_map('sanitize', $newListingData);


        // Validate required fields
        $requiredFields = ['title', 'description',  'salary', 'email', 'city', 'state'];

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
            $fields = [];

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(', ', $fields);

            // values
            $values = [];

            foreach ($newListingData as $field => $value) {
                // Convert empty strings to null
                // inspect($newListingData[$field]);
                if ($value === '') {
                    // set the value to null
                    $newListingData[$field] = null;
                }
                // place holders have the :name
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);
            // inspect($values);
            Session::setFlashMessage('success_message', 'Listing created successfully');
            redirect('/listings');
        }
    }


    /**
     * delete a listing 
     * @param array $params
     * @return void
     */


    public function destroy($params)
    {
        // check if there 
        // params array as we are binding when passed to
        // Database Query

        // inspectAndDie('hello');
        $id = $params['id'];
        // inspect($id);
        $params = [
            'id' => $id,
        ];

        // gets the single listing from the database
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // Check if listing exists
        // if no lising then throw an error
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }


        // Authorization
        // if (Session::get('user')['id'] != $listing->user_id) {
        //     $_SESSION['error_message'] = 'You are not authoirzed to delete this listing';
        //     return redirect("/listings/{$id}");
        // }

        // Authorization -- show error message and re-direct
        if (!Authorization::isOwner($listing->user_id)) {
            // $_SESSION['error_message'] = 'You are not authoirzed to delete this listing';
            Session::setFlashMessage('error_message', 'You are not authoirzed to delete this listing');
            return redirect("/listings/{$id}");
        }
        // inspectAndDie($listing);

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        // Set flash message 
        // $_SESSION['success_message'] = 'Listing deleted successfully';
        Session::setFlashMessage('success_message', 'Listing deleted successfully');


        redirect('/listings');
    }


    /*
   * Show the edit listing form
   *
   * @param array $params
   * @return void
   */
    public function edit($params)
    {


        $id = $params['id'];

        $params = [
            'id' => $id,
        ];

        // fetch single listing 
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // **** THIS STOPS THE EDIT VIEW FROM BEING SHOWN *** //
        // Authorization - TO STOP THE FORM FROM BEING SHOWN
        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authoirzed to update this listing');
            return redirect('/listings/' . $listing->id);
        }

        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }



        // use inspect to see data in screen
        // inspectAndDie($listing);
        // load view and pass that listing into it 
        // listings/edit.view
        // helper func:  $viewPath = basePath("App/views/{$name}.view.php");
        loadView('listings/edit', [
            'listing' => $listing,
        ]);
    }

    /*
   * Update a listing
   *
   * @param array $params
   * @return void
   */
    /*
   * Update a listing
   *
   * @param array $params
   * @return void
   * 
   * mix of the snow and the store()
   */
    public function update($params)
    {
        $id = $params['id'];

        $params = [
            'id' => $id,
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // --- up to this point the same

        // Authorization
        if (!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authoirzed to update this listing');
            return redirect('/listings/' . $listing->id);
        }

        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $updateValues = [];

        // ** same but using a loop insteas of array intersect
        // loop through the allowed fields and  check the allowed fields, if set in POST data push onto the $updatedValues array
        // foreach ($allowedFields as $field) {
        //     if (isset($_POST[$field])) {
        //         $updateValues[$field] = $_POST[$field];
        //     }
        // }


        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));


        // inspectAndDie($updateValues);

        // run the sanitize funciton on every input
        $updateValues = array_map('sanitize', $updateValues);

        // Validate required fields
        $requiredFields = ['title', 'description', 'email', 'city', 'state'];

        // loop and push onto errors and check if empty 
        // Validation is the string method
        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        // inspectAndDie($errors);


        // if there are errors then display the errors and exit
        if (!empty($errors)) {
            loadView('listings/edit', [
                // we pass in $listing as what ever is in the datbase will be put back ther untouched as we have an error
                'listing' => $listing,
                'errors' => $errors,
            ]);
            exit;
            // DYNAMICLY BUILDING THE QUERY!
        } else {
            // loop through to get the sql data structure right for dynamic insert
            //  if no errors then UPDATE and submit to database
            // inspectAndDie('success!');
            // create update fields array 
            $updateFields = [];
            // loop through the keys of updateValues and add them to the update fields array
            foreach (array_keys($updateValues) as $field) {
                // inspect($field);
                // set :placeholder in the query
                $updateFields[] = "{$field} = :{$field}";
            }
            // WE NOW HAVE TO TURN THIS ARRAY INTO A STRING
            // WE NOW HAVE TO TURN THIS ARRAY INTO A STRING
            // inspectAndDie($updateFields);
            $updateFields = implode(', ', $updateFields);
            // inspectAndDie($updateFields);

            // MAKE QUERY AS PLACE HOLDERS
            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";
            // inspectAndDie($updateQuery);


            // Execute the update query
            // SET THE ID AS WE NEED TO BIND THIS IN THE QUERY
            // IN THE DATABASE CLASS
            $updateValues['id'] = $id;

            // inspectAndDie($updateValues);

            // PASS IN QUERY AND VALUES THAT WILL BE BINDED IN THE QUERY
            $this->db->query($updateQuery, $updateValues);

            // flash message
            Session::setFlashMessage('success_message', 'Listing updated successfully');

            //  REDIRECT BACK TO THE EDITED LISTING 
            redirect('/listings/' . $id);
        }
    }


    /*
   * Search listings
   * use the GET super global to inspect the url query params
   * @return void
   */
    public function search()
    {
        // inspectAndDie($_GET);

        // Get the keywords and location from the search form
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        // matches the keywords to keywords and location to location
        $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords OR company LIKE :keywords) AND (city LIKE :location OR state LIKE :location)";
        /**
         * % means 0 or more chars before / after 
         * as long as % is there we get it anywhere in the string 
         */
        $params = [
            'keywords' => "%{$keywords}%",
            'location' => "%{$location}%",
        ];

        $listings = $this->db->query($query, $params)->fetchAll();

        // inspectAndDie($listings);

        // WE SANITIZE WITH HTML SPECIAL CHARS IN THE VIEW
        // AS THIS IS A USER INPUT
        loadView('/listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location,
        ]);
    }
}
