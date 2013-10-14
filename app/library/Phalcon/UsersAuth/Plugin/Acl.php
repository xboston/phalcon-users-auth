<?php

namespace Phalcon\UsersAuth\Plugin;

use Phalcon\Events\Event , Phalcon\Mvc\Dispatcher , Phalcon\Mvc\User\Plugin;


/**
 * Phalcon\UserPlugin\Plugin\Security
 * Check user auth
 *
 * @property  \Phalcon\UsersAuth\Library\Auth\Auth $auth
 */
class Acl extends Plugin
{

    /**
     * beforeExecuteRoute
     *
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function beforeExecuteRoute(Event $event , Dispatcher $dispatcher)
    {

        if ( !$this->auth->getUser() && $this->auth->hasRememberMe() ) {

            $this->auth->loginWithRememberMe();
        }
    }
}
