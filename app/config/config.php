<?php

return new \Phalcon\Config([
    'database'    => [
       'adapter'  => 'Mysql' ,
       'host'     => 'localhost' ,
       'username' => 'root' ,
       'password' => '' ,
       'dbname'   => 'phalcon-auth' ,
       'charset'  => 'utf8'
    ] ,
    'application' => [
       'controllersDir' => __DIR__ . '/../../app/controllers/' ,
       'modelsDir'      => __DIR__ . '/../../app/models/' ,
       'viewsDir'       => __DIR__ . '/../../app/views/' ,
       'pluginsDir'     => __DIR__ . '/../../app/plugins/' ,
       'libraryDir'     => __DIR__ . '/../../app/library/' ,
       'cacheDir'       => __DIR__ . '/../../app/cache/' ,
       'baseUri'        => '/' ,
    ] ,
    'plugins'     => [
       'UsersAuth' => [
           'redirects' => [
               'afterLogin'                  => false ,
               'afterLoginWithRemember'      => 'sdf' ,
               'afterLoginWithRememberError' => 'sdf' ,
           ]
       ]
    ]
]
);
