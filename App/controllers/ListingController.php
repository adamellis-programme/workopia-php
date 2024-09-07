<?php

namespace App\Controllers;

use Framework\Database;

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
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();

        require loadView('listings/index', [
            'listings' => $listings
        ]);
    }

    public function create()
    {
         loadView('listings/create');
    }
    public function show()
    {

        $id = $_GET['id'] ?? '';
        // inspect($id);


        $params = [
            'id' => $id,
        ];
        // Use prepared statements and never this way 
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        // inspect($listing);
         loadView('listings/show', [
            'listing' => $listing,
        ]);
    }
}
