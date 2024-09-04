<?php
$routes = require basePath('routes.php');
// the keys are the uri
// look for the uri in the routes file
if (array_key_exists($uri, $routes)) {
    // inspectAndDie($routes[$uri]);
    // if it is there then require this file which will losd that page 
    require basePath($routes[$uri]);
} else {
    // if the key does not exist route to the 404 controllers/error/404.php' which will then route to the view
    http_response_code(404);
    require basePath($routes['404']);
}
