<?php
// FRONT CONTROLLER
/*
    Decides which controller and action to run, based on the route

    echo 'Requested URL = "' . $_SERVER['QUERY_STRING'] . '"';

    require('../Core/Router.php');
    $router = new Router();
    echo get_class($router);
 */

// Require controller class
//require('../App/Controllers/Posts.php');

/*
 * Twig
 */
require_once '../vendor/autoload.php';

/*
 * Autoloader
 */
/* NB! KASUTAME COMPOSER AUTOLOADI
spl_autoload_register(function ($class) {
   $root = dirname(__DIR__); // get the parent directory
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_readable($file)) {
        require $root . '/' . str_replace('\\', '/', $class) . '.php';
    }
});
*/

/*
 * Error and exception handling
 */
error_reporting(E_ALL); // make sure we see every single error if there is one
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

// Sessions
session_start();

// Routing
require('../Core/Router.php');
$router = new Core\Router();

// add to routes
$router->addNewRoute('', ['controller' => 'Home', 'action' => 'index']);
$router->addNewRoute('login', ['controller' => 'Login', 'action' => 'new']);
$router->addNewRoute('logout', ['controller' => 'Login', 'action' => 'logout']);
$router->addNewRoute('signup', ['controller' => 'Signup', 'action' => 'signup']);
$router->addNewRoute('profile', ['controller' => 'Profile', 'action' => 'profile']);
$router->addNewRoute('{controller}/{action}');
$router->addNewRoute('{controller}/{action}/{id:\d+}');
$router->addNewRoute('admin/{controller}/{action}', ['namespace' => 'Admin']);

/*
echo '<pre>';
echo htmlspecialchars(print_r($router->getRouteTable(), true));
echo '<pre>';

// match the requested root
$url = $_SERVER['QUERY_STRING'];

if ($router->match($url)) {
    echo '<pre>';
    var_dump($router->getParameters());
    echo '<pre>';
} else {
    echo "No route found for route '$url'";
}

*/
$router->dispatch($_SERVER['QUERY_STRING']);
