<?php
// -: in Class declare namespace buy using namespace Framework
// -: then in every file that needs access
namespace App\Controllers;

// -: because we are using namespaces App\Controllers
// -: we do not need to require this HomeController in other files
// -: use the die fucntion to test it works:: die('home controller ');
// -: 
// -: 


class HomeController
{
    public function __construct() {}

    public function index()
    {
        die('homeController@index');
    }
}
