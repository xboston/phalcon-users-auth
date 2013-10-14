<?php

namespace Phalcon\UsersAuth\Plugin;

use Phalcon\Events\Event , Phalcon\Mvc\Dispatcher , Phalcon\Mvc\User\Plugin;

/**
 * Phalcon\UserPlugin\Plugin\Security
 * @property  \Phalcon\UsersAuth\Library\Auth\Auth $auth
 */
class Auth extends Plugin
{

    /**
     * beforeExecuteRoute action
     *
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    public function beforeExecuteRoute(Event $event , Dispatcher $dispatcher)
    {
        if ( $this->auth->hasRememberMe() ) {

            $this->auth->loginWithRememberMe();
        }

        $user = $this->auth->getUser();
        $this->view->setVar('authUser',$user);
    }

}
