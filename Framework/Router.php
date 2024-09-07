<?php
// this is called framework as it is  not specific to any rescurce it can be users / listing etc

namespace Framework;

use App\Controllers\ErrorController;

// $routes = require basePath('routes.php');
// // the keys are the uri
// // look for the uri in the routes file
// if (array_key_exists($uri, $routes)) {
//     // inspectAndDie($routes[$uri]);
//     // if it is there then require this file which will losd that page 
//     require basePath($routes[$uri]);
// } else {
//     // if the key does not exist route to the 404 controllers/error/404.php' which will then route to the view
//     http_response_code(404);
//     require basePath($routes['404']);
// }



/**
 * Router for the application
 */
// 4 methods that handle the http methods 
class Router

{
    protected $routes = [];
    /**
     * Add a new Route
     * this is a method 
     * @param String  $method
     * @param String  $uri
     * @param String  $action
     * @return void
     */
    public function registerRoute($method, $uri, $action)
    {
        // assos arr method
        // NOTE THE BRACKETS [] = ADDING TO THE ARRAY AND NOT SETTING THE ARRAY 
        list($controller, $controllerMethod) = explode('@', $action);
        // inspectAndDie($controllerMethod);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
        ];
    }


    public function debugRoutes()
    {
        inspect($this->routes);
        // echo '<pre>';
        // print_r($this->routes);
        // echo '<pre>';
    }


    /**
     * Add a GET route to the router
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */

    /**
     * in routes.php we call $router->get and
     * pass in method and pass in only uri and controller
     * here we pass in the first param which is the 
     * METHOD 'GET'
     * 
     * each time this is called we ADD (PUSH ON) a route to the routes array!
     * 
     */
    public function get($uri, $controller)
    {
        $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Add a POST route to the router
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {

        $this->registerRoute('POST', $uri, $controller);
    }

    /**
     * Add a PUT route to the router
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Add a DELETE route to the router
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller)
    {
        $this->registerRoute('DELETE', $uri, $controller);
    }



    /**
     * Route the request
     *
     * @param string $uri
     * @param string $method
     * @return void
     */
    // from INDEX.HTML SUPER GLOBALS
    // URI AND METHOD is what comes from index $_gloobals
    // here we need to loop thorugh all the routes, listings, create, etc
    // to see if it is matching the uri that is being called 
    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            // inspect($route);
            // if the uri in the routes matches the uri we are accessing through the browser
            // $route['WHATEVER'] = current iteration 
            // uri and method come throm the super global
            if ($route['uri'] === $uri && $route['method'] === $method) {
                // require basePath('App/' . $route['controller']);
                // if true load the controller for that particular route 
                //  ----
                // extract controller and controller method

                $controller = 'App\\Controllers\\' . $route['controller'];
                $controllerMethod = $route['controllerMethod'];

                // contrroller is a class, her we instantiate and call the method 
                // this controller can be users/ listing / anything etc
                $controllerInstance = new $controller();
                // inspectAndDie($controllerInstance);

                $controllerInstance->$controllerMethod(); // method called in controller
                return;
                // by passing in the $route['key'] 
                // and key we choose the VALUE!
            }

            // if we try and load a route that is not in the array 

        }

        // error controller is in  the APP namespace 
        // $this->error == was this but we do not have 
        // to instantiate as we USED STATIC METHODS
        ErrorController::notFound(); // IN APP NAME SPACE
    }
}
