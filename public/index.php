
<?php

require '../helpers.php';
require basePath('Database.php');
require basePath('Router.php');

// Instantiate the router
$router = new Router();

// Get Routes
$routes = require basePath('routes.php');

// Get cuttent URI adn HTTP method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// inspectAndDie($method);


// Route the request
$router->route($uri, $method);
