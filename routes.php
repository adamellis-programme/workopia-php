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

// keep the gets together
$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/create', 'ListingController@create');

$router->get('/listings/edit/{id}', 'ListingController@edit');
$router->get('/listings/{id}', 'ListingController@show');
// because of classes we can specify a method 




// @ is broken up and show, create, destroy are used to call the methods


// $router->debugRoutes();

// NOTE: THE METHOD IS @... AND IT WILL BE IN FOLDER ListingController
// IF WE GO T TO /.../... IN THE URL IT RUNS THIS CONTROLLER

//  in listing controller create a POST
$router->post('/listings', 'ListingController@store');
// FORM SUBMITS TO /LISTINGS/ID href="/listings/<\?= $listing->id \?\>
$router->put('/listings/{id}', 'ListingController@update');
// click a buttom and calls a method in our controller called destroy
// then go to the views/listings/show to make the form request a delete with a spoof 
// keep {id} at the bottom so it does not think /edit is an id
$router->delete('/listings/{id}', 'ListingController@destroy');


// user controller 
//      load the form
$router->get('/auth/register', 'UserController@create');
$router->get('/auth/login', 'UserController@login');

// some people have a seperate session controller 
// this is kept in one controller

// auth
//       submit the form
$router->post('/auth/register', 'UserController@store');


