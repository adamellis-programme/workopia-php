<?php

// we include this here so we do not have to call it again in other files

// require basePath('views/home.view.php');
require '../helpers.php';
// require loadView('home');

// a router can be a switch statment or an object
// assoss  array and mappded to a controller file
// any time we go to a route that doesnot exitst we go to the 404



// get uri using server super global
$uri = $_SERVER['REQUEST_URI'];
// inspectAndDie($uri);


require basePath('router.php');


// inspectAndDie($uri);