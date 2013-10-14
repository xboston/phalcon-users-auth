<?php

define('ROOT' , dirname(__DIR__));

//error_reporting(E_ALL);

(new Phalcon\Debug())->listen();

/**
 * Read the configuration
 */
$config = include __DIR__ . "/../app/config/config.php";

/**
 * Read auto-loader
 */
include __DIR__ . "/../app/config/loader.php";

/**
 * Read services
 */
include __DIR__ . "/../app/config/services.php";

/**
 * Handle the request
 */
echo (new \Phalcon\Mvc\Application($di))->handle()->getContent();
