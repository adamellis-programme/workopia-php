
<?php
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;
// inspect(__DIR__);
// require basePath('Framework/Database.php');
// require basePath('Framework/Router.php');

// CUSOTM AUTO LOADER
// spl_autoload_register(function ($class) {
//     $path = basePath('Framework/' . $class . '.php');
//     if (file_exists($path)) {
//         require $path;
//     }
// });

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


// inspectAndDie($method);


// Route the request
$router->route($uri);
