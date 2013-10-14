<?php

$router = new Phalcon\Mvc\Router(false);

$router->removeExtraSlashes(true);
$router->notFound([ 'controller' => 'error' , 'action' => 'error404' ]);

$router->add('/' , 'Users::index')->setName('index');

$router->add('/register' , 'Users::register')->setName('register');
$router->add('/confirm-email/{code}/{email}' , 'Users::confirmEmail')->setName('confirm-email');

$router->add('/login' , 'Users::login')->setName('login');
$router->add('/logout' , 'Users::logout')->setName('logout');

$router->add('/profile' , 'Users::profile')->setName('profile');
$router->add('/edit-profile' , 'Users::editProfile')->setName('edit-profile');

$router->add('/change-password' , 'Users::changePassword')->setName('change-password');
$router->add('/forgot-password' , 'Users::forgot')->setName('forgot-password');
$router->add('/reset-password/{code}/{email}' , 'Users::resetPassword')->setName('reset-password');

return $router;
