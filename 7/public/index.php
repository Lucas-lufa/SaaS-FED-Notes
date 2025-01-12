<?php 

require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;
use Framework\Session;

Session::start();

// Insatiate the router
$router = new ROuter();

// Get routes
$routes = require basePath('routes.php');

// get current uri and http method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// echo password_hash("Password1", PASSWORD_DEFAULT);
// die;
// Route the request
$router->route($uri);