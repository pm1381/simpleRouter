<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

require_once './src/index.php';

// Run it!
$router->run();