<?php

// return [
//     '/' => 'controllers/home.php',
//     '/listings' => 'controllers/listings/index.php',
//     '/listings/create' => 'controllers/listings/create.php',
//     '404' => 'controllers/error/404.php' // Add this route
// ];


// 1:- set up listing class
// 2:- set listing route below 
// 1:- 


$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/create', 'ListingController@create');
$router->get('/listings/{id}', 'ListingController@show');
// because of classes we can specify a method 

//  in listing controller create a POST
$router->post('/listings', 'ListingController@store');

// @ is broken up and show, create, destroy are used to call the methods


// $router->debugRoutes();

// click a buttom and calls a method in our controller called destroy
// then go to the views/listings/show to make the form request a delete with a spoof 
$router->delete('/listings/{id}', 'ListingController@destroy');
