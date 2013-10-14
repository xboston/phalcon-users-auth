<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
         $config->application->controllersDir ,
         $config->application->modelsDir
    )
)->registerNamespaces(
        [
        'Phalcon\UsersAuth' => $config->application->libraryDir . 'Phalcon/UsersAuth' ,
        ]
    )->register();
