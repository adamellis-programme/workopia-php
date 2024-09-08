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
        // inspect($uri);
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
    public function route($uri)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Check if the request is a POST and contains the _method parameter
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            // Override the request method with the value of _method
            // _POST IS THE ACTUAL DATA 
            $requestMethod = strtoupper($_POST['_method']);
            // inspect($requestMethod);
        }
        // URI comes from the public index.php
        // inspect($uri);

        // Split the URI into segments
        $uriSegments = explode('/', trim($uri, '/'));
        // inspect(count($uriSegments));
        // inspect($uriSegments);
        foreach ($this->routes as $route) {

            // Split the ROUTE  into segments
            $routeSegments = explode('/', trim($route['uri'], '/'));
            // inspect(count($routeSegments));
            // inspect($route['uri']);

            // Check if the number of segments matches
            if (count($uriSegments) === count($routeSegments) && strtoupper($route['method']) === $requestMethod) {
                $params = [];

                // Compare each segment
                $match = true;

                for ($i = 0; $i < count($uriSegments); $i++) {
                    // THIS IS JUST A WAY TO CHECK AND BREAK
                    // if not true to the current i variable
                    // If the segments don't match (and it's not a parameter), it means the URI doesn't match the route and the function can stop checking this route.
                    if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        // echo $routeSegments[$i];
                        // inspect($uriSegments[$i]);
                        // inspect($routeSegments[$i]);
                        $match = false;
                        break;
                        // does break throw an error

                    }

                    /**
                     * 
                     */

                    // inspect($routeSegments);
                    // $routeSegments[$i] returns lisitn and {id} / create
                    // routeSegments are the "/listings/create"  LAST SEGMENTS OF THE 
                    // EXPLODED STRING
                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        // This segment is a parameter, so store it
                        // **** MATCHES COULD BE NAME => 'SARAH'
                        // **** WE THEN SET THE KEYS AND VALUES TO BE PASSED TO THE CONTROLLER

                        // MANY PARAMS WILL LOOP AND PUSH KEY VALUE PAIRS ONTO THE PARAMS ARRAY 

                        // KEY matches[1]-----> VALUE  uriSegments
                        $params[$matches[1]] = $uriSegments[$i];
                        // inspect($matches[1]);
                        // inspect($matches); // listing example: array with {id} and id 
                        // inspect($uriSegments[1]);
                        // inspect($params);
                    }
                }

                /**
                 * 
                 * 
                 */

                if ($match) {
                    // Extract controller and method from route
                    // inspect($route);
                    // controller gets the file name whic h has the correct controller class in it 

                    // this line is basicly App/Controllers/listingController
                    // this line is basicly App/Controllers/listingController
                    // this line is basicly App/Controllers/listingController
                    // \ escapes the string
                    $controller = 'App\\Controllers\\' . $route['controller'];
                    // inspect($route['controller']);
                    // inspect($controller);

                    // this gets us the METHOD this is on the CONTROLLER CLASS FILE
                    $controllerMethod = $route['controllerMethod'];
                    // inspect($controllerMethod);

                    // Instantiate the controller and 
                    $controllerInstance = new $controller();


                    /**
                     * grabs the "App\Controllers\ListingController" file 
                     * calls the 'show' method in that file
                     * passes the params to that file 
                     * 
                     * say we are calling the lising controller which has one CLASS in it
                     * we call that instance and call a method 
                     * the method in the lisings case maybe 'show'
                     * we have now called 'show'
                     * we pass $params into 'show' 
                     * which is a mehod in the controller class
                     * $parms is in this case id => "16"
                     */

                    // call the method, passing parameters
                    // inspect($params);
                    // PARAMS IS AN ARRAY THAT CAN HAVE AS MANY PARAMS AS WE NEED 
                    // SUCH AS NAME=, AGE=, ID= ETC
                    $controllerInstance->$controllerMethod($params);
                    // inspect($controllerInstance);
                    return;
                }
            }
        }


        // error controller is in  the APP namespace 
        // $this->error == was this but we do not have 
        // to instantiate as we USED STATIC METHODS
        ErrorController::notFound(); // IN APP NAME SPACE
    }
}
