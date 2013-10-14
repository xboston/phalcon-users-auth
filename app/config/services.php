<?php

use Phalcon\DI\FactoryDefault , Phalcon\Mvc\View , Phalcon\Mvc\Url as UrlResolver , Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter , Phalcon\Mvc\View\Engine\Volt as VoltEngine ,
    Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set(
    'url' ,
    function () use ($config) {
        $url = new UrlResolver();
        $url->setBaseUri($config->application->baseUri);

        return $url;
    } ,
    true
);

/**
 * Setting up the view component
 */
$di->set(
    'view' ,
    function () use ($config) {

        $view = new View();

        $view->setViewsDir($config->application->viewsDir);

        $view->registerEngines(
            array(
                 '.volt'  => function ($view , $di) use ($config) {

                         $volt = new VoltEngine($view , $di);

                         $volt->setOptions(
                             array(
                                  'compiledPath'      => $config->application->cacheDir ,
                                  'compiledSeparator' => '_'
                             )
                         );

                         return $volt;
                     } ,
                 '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            )
        );

        return $view;
    } ,
    true
);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set(
    'db' ,
    function () use ($config) {
        return new DbAdapter((array) $config->database);
    } ,
    true
);

$di->set(
    'flash' ,
    function () {
        return new Phalcon\Flash\Direct(array(
                                             'error'   => 'ui red pointing above ui label' ,
                                             'success' => 'ui green message' ,
                                             'notice'  => 'ui yellow message' ,
                                        ));
    } ,
    true
);

$di->setShared(
    'router' ,
    function () {
        return require __DIR__ . '/routes.php';
    }
);


/**
 * Phalcon\UsersAuth
 *
 */

/**
 * Start the session the first time some component request the session service
 */
$di->setShared(
    'session' ,
    function () {

        ini_set( 'session.cookie_httponly', 1 );

        $session = new SessionAdapter();

        $session->start();

        return $session;
    }
);

/**
 * Encryption service
 */
$di->setShared(
    'crypt' ,
    function () use ($config) {

        return (new \Phalcon\Crypt())->setKey('1234');
    }
);

/**
 * Custom authentication component
 */
$di->setShared(
    'auth' ,
    function () {

        return new Phalcon\UsersAuth\Library\Auth\Auth();
    }
);

/**
 * Mail service
 */
$di->setShared(
    'mail' ,
    function () {

        return new Phalcon\UsersAuth\Library\Mail\Mail();
    }
);

/**
 * Access Control List
 */
$di->setShared(
    'acl' ,
    function () {

        return new Phalcon\UsersAuth\Library\Acl\Acl();
    }
);

$di->setShared(
    'dispatcher' ,
    function () use ($di) {

        $eventsManager = $di->getShared('eventsManager');

        $acl_plugin = new Phalcon\UsersAuth\Plugin\Acl($di);
        $eventsManager->attach('dispatch' , $acl_plugin);

        $dispatcher = new Phalcon\Mvc\Dispatcher();

        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    }
);
