<?php

namespace Phalcon\UsersAuth\Library\Auth {

    use Phalcon\Mvc\User\Component , Phalcon\UsersAuth\Models\Users , Phalcon\UsersAuth\Models\RememberTokens , Phalcon\UsersAuth\Models\SuccessLogins , Phalcon\UsersAuth\Models\FailedLogins ,
        Phalcon\UsersAuth\Library\Auth\Exception\Exception;

    /**
     * Phalcon\UsersAuth\Auth\Auth
     *
     * Manages Authentication/Identity Management
     *
     * @property \Phalcon\Http\Cookie $cookies
     */
    class Auth extends Component
    {

        /**
         * Checks the user credentials
         *
         * @param $credentials
         *
         * @throws Exception
         */
        public function check($credentials)
        {

            //Check if the user exist
            $user = Users::findFirstByEmail($credentials['email']);

            if ( $user == false ) {

                $this->registerUserThrottling(0);
                throw new Exception('Wrong email/password combination');
            }

            //Check the password
            if ( !$this->security->checkHash($credentials['password'] , $user->password) ) {

                $this->registerUserThrottling($user->id);
                throw new Exception('Wrong email/password combination');
            }

            //Check if the user was flagged
            $this->checkUserFlags($user);

            //Register the successful login
            $this->saveSuccessLogin($user);

            //Check if the remember me was selected
            if ( isset($credentials['remember']) ) {

                $this->createRememberEnvironment($user);
            }

            $this->session->set(
                'auth-identity' ,
                array(
                     'id'      => $user->id ,
                     'name'    => $user->name ,
                     'profile' => $user->profile->name
                )
            );
        }

        /**
         * Creates the remember me environment settings the related cookies and generating tokens
         *
         * @param \Phalcon\UsersAuth\Models\Users $user
         *
         * @throws Exception
         */
        public function saveSuccessLogin($user)
        {
            $success_login             = new SuccessLogins();
            $success_login->users_id   = $user->id;
            $success_login->ip_address = $this->request->getClientAddress();
            $success_login->user_agent = $this->request->getUserAgent();

            if ( !$success_login->save() ) {

                $messages = $success_login->getMessages();
                throw new Exception($messages[0]);
            }
        }

        /**
         * Implements login throttling
         * Reduces the effectiveness of brute force attacks
         *
         * @param int $user_id
         */
        public function registerUserThrottling($user_id)
        {
            $failed_login             = new FailedLogins();
            $failed_login->users_id   = $user_id;
            $failed_login->ip_address = $this->request->getClientAddress();
            $failed_login->attempted  = time();
            $failed_login->save();

            $attempts = FailedLogins::count(
                array(
                     'ip_address = ?0 AND attempted >= ?1' ,
                     'bind' => array(
                         $this->request->getClientAddress() ,
                         time() - 3600 * 6
                     )
                )
            );

            switch ( $attempts ) {
                case 1:
                case 2:
                    // no delay
                    break;
                case 3:
                case 4:
                    sleep(2);
                    break;
                default:
                    sleep(4);
                    break;
            }

        }

        /**
         * Creates the remember me environment settings the related cookies and generating tokens
         *
         * @param \Phalcon\UsersAuth\Models\Users $user
         */
        public function createRememberEnvironment(Users $user)
        {

            $user_agent = $this->request->getUserAgent();
            $token      = md5($user->email . $user->password . $user_agent);

            $remember             = new RememberTokens();
            $remember->users_id   = $user->id;
            $remember->token      = $token;
            $remember->user_agent = $user_agent;

            if ( $remember->save() != false ) {

                $expire = time() + 86400 * 8;

                $this->cookies->set('RMU' , $user->id , $expire);
                $this->cookies->set('RMT' , $token , $expire);
            }

        }

        /**
         * Check if the session has a remember me cookie
         *
         * @return boolean
         */
        public function hasRememberMe()
        {
            return $this->cookies->has('RMU');
        }

        /**
         * Logs on using the information in the cookies
         *
         * @return \Phalcon\Http\Response
         */
        public function loginWithRememberMe()
        {
            $user_id      = $this->cookies->get('RMU')->getValue();
            $cookie_token = $this->cookies->get('RMT')->getValue();

            $user = Users::findFirstById($user_id);
            if ( $user ) {

                $user_agent = $this->request->getUserAgent();
                $token      = md5($user->email . $user->password . $user_agent);

                if ( $cookie_token == $token ) {

                    $remember = RememberTokens::findFirst(
                        [
                        'users_id = ?0 AND token = ?1' ,
                        'bind' => [ $user->id , $token ]
                        ]
                    );
                    if ( $remember ) {

                        //Check if the cookie has not expired
                        if ( (time() - (86400 * 8)) < $remember->created_at ) {

                            //Check if the user was flagged
                            $this->checkUserFlags($user);

                            //Register identity
                            $this->session->set(
                                'auth-identity' ,
                                [
                                'id'      => $user->id ,
                                'name'    => $user->name ,
                                'profile' => $user->profile->name
                                ]
                            );

                            //Register the successful login
                            $this->saveSuccessLogin($user);

                            return $this->response->redirect('user');
                        }
                    }

                }

            }

            $this->cookies->get('RMU')->delete();
            $this->cookies->get('RMT')->delete();

            return $this->response->redirect('user/login');
        }

        /**
         * Checks if the user is banned/inactive/suspended
         *
         * @param \Phalcon\UsersAuth\Models\Users $user
         *
         * @throws \Phalcon\UsersAuth\Library\Auth\Exception\Exception
         */
        public function checkUserFlags(Users $user)
        {
            if ( $user->active <> 'Y' ) {

                throw new Exception('The user is inactive');
            }

            if ( $user->banned <> 'N' ) {

                throw new Exception('The user is banned');
            }

            if ( $user->suspended <> 'N' ) {

                throw new Exception('The user is suspended');
            }
        }

        /**
         * Returns the current identity
         *
         * @return array
         */
        public function getIdentity()
        {
            return $this->session->get('auth-identity');
        }

        /**
         * Returns the current identity
         *
         * @return string
         */
        public function getName()
        {
            $identity = $this->session->get('auth-identity');

            return $identity['name'];
        }

        /**
         * Removes the user identity information from session
         */
        public function remove()
        {
            if ( $this->cookies->has('RMU') ) {

                $this->cookies->get('RMU')->delete();
            }

            if ( $this->cookies->has('RMT') ) {

                $this->cookies->get('RMT')->delete();
            }

            $this->session->remove('auth-identity');
        }

        /**
         * Auths the user by his/her id
         *
         * @param $id
         *
         * @throws \Phalcon\UsersAuth\Library\Auth\Exception\Exception
         */
        public function authUserById($id)
        {
            $user = Users::findFirstById($id);
            if ( $user == false ) {

                throw new Exception('The user does not exist');
            }

            $this->checkUserFlags($user);

            $this->session->set(
                'auth-identity' ,
                array(
                     'id'      => $user->id ,
                     'name'    => $user->name ,
                     'profile' => $user->profile->name
                )
            );

        }

        /**
         * Get the entity related to user in the active identity
         *
         * @return bool|\Phalcon\UsersAuth\Models\Users
         * @throws \Phalcon\UsersAuth\Library\Auth\Exception\Exception
         */
        public function getUser()
        {
            $identity = $this->session->get('auth-identity');
            if ( isset($identity['id']) ) {

                $user = Users::findFirstById($identity['id']);
                if ( $user == false ) {

                    throw new Exception('The user does not exist');
                }

                return $user;
            }

            return false;
        }
    }
}
