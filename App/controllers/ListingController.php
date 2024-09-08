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
}
