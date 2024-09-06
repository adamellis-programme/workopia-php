<?php
//  ASK CHAT WHY WE USE REQUIRE OVER RETURN IN THE HELPERS 
use Framework\Database;

$config = require basePath('config/db.php');

$db = new Database($config);

// call the query method on the db adn pass in the query
$listings = $db->query('SELECT * FROM listings LIMIT 6')->fetchAll();
// inspect($listings);

$hello = 'hey';

require loadView('listings/index', [
    'listings' => $listings,
    'test' => $hello,
]);
