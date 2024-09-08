<?php


/**
 * Get the base path
 *
 * takes in a path
 * @param string $path
 * @return string
 */

function basePath($path = '')
{
    // gets us the absolute path __DIR__
    // echo __DIR__;
    return __DIR__ . '/' . $path;
}


/**
 * dynamic view loading 
 * Load a view
 *
 * @param string $name
 * @param array $data
 * @return void
 */


//  .If extract() is called inside a function, it adds variables to the local symbol table of that function.§§
//  If extract() is called in a script that includes or requires a view, it adds variables to the symbol table of the scope where the view will be loaded, making those variables available in the view.
// variable interpolation  {}
function loadView($name, $data = [])
{

    $viewPath = basePath("App/views/{$name}.view.php");
    // var_dump($viewPath);

    // inspect($viewPath); 

    // Make sure path exists
    if (file_exists($viewPath)) {
        $test1 = 'hello';

        extract($data);
        require  $viewPath;
        // require  $viewPath;
    } else {
        echo "View '{$name}' not found.";
    }
}


/**
 * Load a partial
 *
 * @param string $name
 * @param array $data
 * @return void
 */

//  ASK CHAT WHY WE USE REQUIRE OVER RETURN IN THE HELPERS 
// the difference between using returr and require 
// is if the return is called here then the require has
// to be called on the page
function loadPartial($name)
{
    $partialPath = basePath("App/views/partials/{$name}.php");

    // Make sure path exists
    if (file_exists($partialPath)) {
        // returning the require statement here
        require $partialPath;
    } else {
        echo "Partial '{$name}' not found.";
    }
}



/**
 * Inspect a value
 *
 * @param array $values
 * @return void // void as prints out to the screen
 */
function inspect($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}



/**
 * Inspect a value and die
 * 
 * LESS CONFUSING AS THERE WILL BE NOTHING ELSE ON THE SCREEN OTHER THAN THE INSPECT DUMP!
 *
 * @param mixed $value
 * @return void
 */
function inspectAndDie($value)
{
    echo '<pre>';
    die(var_dump($value));
    echo '</pre>';
    // or just use die() here 
}



/**
 * Format Salary
 *
 * @param string $salary
 * @return string $formattedSalary
 */
function formatSalary($salary)
{
    // floatval
    return '$' . number_format(floatval($salary));
}


/**
 * Sanitize data
 *
 * @param string $dirty
 * @return string
 */
function sanitize($dirty)
{
    return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}
