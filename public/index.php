
<?php
// This is where everything starts 
// we include this here so we do not have to call it again in other files

// require basePath('views/home.view.php');
require '../helpers.php';
// require loadView('home');

// a router can be a switch statment or an object
// assoss  array and mappded to a controller file
// any time we go to a route that doesnot exitst we go to the 404





require basePath('Router.php');
// as Router file is now called we can instantiate a new Roter here in index.php
// instantiate a new router 
// call the router 

$router = new Router();
$routes = require basePath('routes.php');



// get uri using server super global
// GET THE URI AND METHOD THAT IS BEING REQUESTED 
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
// inspect($uri);
// inspect($method);

// pass in method of what ever page we are visiting 
$router->route($uri, $method);




// inspectAndDie($uri); 