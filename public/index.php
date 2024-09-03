<?php

// we include this here so we do not have to call it again in other files

// require basePath('views/home.view.php');
require '../helpers.php';
// require loadView('home');

// a router can be a switch statment or an object
// assoss  array and mappded to a controller file
// any time we go to a route that doesnot exitst we go to the 404
$routes = [
    '/' => 'controllers/home.php',
    '/listings' => 'controllers/listings/index.php',
    '/listings/create' => 'controllers/listings/create.php',
    '404' => 'controllers/error/404.php' // Add this route
];


// get uri using server super global
$uri = $_SERVER['REQUEST_URI'];
// inspectAndDie($uri);

// the keys are the uri
// look for the uri in the routes file
if (array_key_exists($uri, $routes)) {
    // inspectAndDie($routes[$uri]);
    // if it is there then require this file which will losd that page 
    require basePath($routes[$uri]);
} else {
    // if the key does not exist route to the 404 controllers/error/404.php' which will then route to the view
    
    require basePath($routes['404']);
}


// inspectAndDie($uri);