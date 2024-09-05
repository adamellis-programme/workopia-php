
<?php

require '../helpers.php';
require basePath('Database.php');
require basePath('Router.php');

// Instantiate the router
$router = new Router();

// Get Routes
$routes = require basePath('routes.php');

// Get cuttent URI adn HTTP method
// PHP_URL_PATH ignores the search params 

/**
 * parse_url()
 * parse_url() is a PHP function that parses a given URL and returns its components (such as the scheme, host, path, query, etc.).
 * It can return a specific part of the URL if you provide a second argument.
 * makes the page load so we can dynamicly pull the parans for the query string 
 * 
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// inspectAndDie($method);


// Route the request
$router->route($uri, $method);
