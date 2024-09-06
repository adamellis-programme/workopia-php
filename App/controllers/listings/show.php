<?php

use Framework\Database;

$config = require basePath('config/db.php');

$db = new Database($config);

$id = $_GET['id'] ?? '';
// inspect($id);


$params = [
    'id' => $id,
];
// Use prepared statements and never this way 
$listing = $db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

// inspect($listing);
require loadView('listings/show', [
    'listing' => $listing,
]);


// Use prepared statements and never this way 
// $listing = $db->query('SELECT * FROM listings WHERE id =' . $id)->fetch();