<?php

namespace Phalcon\UsersAuth\Controllers {

    /**
     * Example UsersController class
     *
     * @property \Phalcon\UsersAuth\Library\Auth\Auth $auth
     */
    class UsersController extends \Phalcon\Mvc\Controller
    {

        public function indexAction()
        {

            $user = $this->auth->getUser();
            print_r(is_object($user) ? $user->toArray() : [ 'не авторизован' ]);

            echo session_name();

        }

        /**
         * Step 1 - register
         *
         * @return mixed
         */
        public function registerAction()
        {

            $form = new \Phalcon\UsersAuth\Forms\RegisterForm();

            if ( $this->request->isPost() ) {

                if ( $form->isValid($this->request->getPost()) != false ) {

                    $user = new \Phalcon\UsersAuth\Models\Users();

                    $user->assign(
                        [
                        'name'        => $this->request->getPost('name' , 'striptags') ,
                        'email'       => $this->request->getPost('email') ,
                        'password'    => $this->security->hash($this->request->getPost('password')) ,
                        'profiles_id' => 2
                        ]
                    );

                    if ( $user->save() ) {

                        return $this->dispatcher->forward(
                            [
                            'action' => 'index'
                            ]
                        );
                    }

                    $this->flash->error($user->getMessages());
                }

            }

            $this->view->form = $form;
        }


        /**
         * Step 2 - confirm email
         *
         * @return mixed
         */
        public function confirmEmailAction()
        {

            $code = $this->dispatcher->getParam('code');

            $confirmation = \Phalcon\UsersAuth\Models\EmailConfirmations::findFirstByCode($code);

            if ( !$confirmation ) {

                return $this->dispatcher->forward([ 'action' => 'index' ]);
            }

            /*
            $email = $this->dispatcher->getParam('email');
            if ( $confirmation->user->email != $email ) {

                $this->flash->error('email and code not found');
                return $this->dispatcher->forward([ 'action' => 'index' ]);
            }
            */


            if ( $confirmation->confirmed <> 'N' ) {

                return $this->dispatcher->forward([ 'action' => 'login' ]);
            }

            $confirmation->confirmed = 'Y';

            $confirmation->user->active = 'Y';

            /**
             * Change the confirmation to 'confirmed' and update the user to 'active'
             */
            if ( !$confirmation->save() ) {

                foreach ( $confirmation->getMessages() as $message ) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward([ 'action' => 'index' ]);
            }

            /**
             * Identity the user in the application
             */
            $this->auth->authUserById($confirmation->user->id);

            /**
             * Check if the user must change his/her password
             */
            if ( $confirmation->user->must_change_password == 'Y' ) {

                $this->flash->success('The email was successfully confirmed. Now you must change your password');

                return $this->dispatcher->forward([ 'action' => 'changePassword' ]);
            }

            $this->flash->success('The email was successfully confirmed');

            return $this->dispatcher->forward([ 'action' => 'index' ]);

        }

        public function loginAction()
        {
            $form = new \Phalcon\UsersAuth\Forms\LoginForm();

            try {

                if ( !$this->request->isPost() ) {

                    if ( $this->auth->hasRememberMe() ) {
                        return $this->auth->loginWithRememberMe();
                    }

                } else {

                    if ( $form->isValid($this->request->getPost()) == false ) {
                        foreach ( $form->getMessages() as $message ) {
                            $this->flash->error($message);
                        }
                    } else {

                        $this->auth->check(
                            array(
                                 'email'    => $this->request->getPost('email') ,
                                 'password' => $this->request->getPost('password') ,
                                 'remember' => $this->request->getPost('remember')
                            )
                        );

                        return $this->response->redirect('user');
                    }
                }

            } catch ( \Phalcon\UsersAuth\Library\Auth\Exception\Exception $e ) {

                $this->flash->error($e->getMessage());
            }

            $this->view->form = $form;
        }

        public function logoutAction()
        {

            $this->auth->remove();

            return $this->response->redirect('user');
        }

        public function forgotAction()
        {

            $form = new \Phalcon\UsersAuth\Forms\ForgotPasswordForm();

            if ( $this->request->isPost() ) {

                if ( $form->isValid($this->request->getPost()) == false ) {
                    foreach ( $form->getMessages() as $message ) {
                        $this->flash->error($message);
                    }
                } else {

                    $user = \Phalcon\UsersAuth\Models\Users::findFirstByEmail($this->request->getPost('email'));
                    if ( !$user ) {
                        $this->flash->notice('There is no account associated to this email');
                    } else {

                        $resetPassword           = new \Phalcon\UsersAuth\Models\ResetPasswords();
                        $resetPassword->users_id = $user->id;
                        if ( $resetPassword->save() ) {

                            $this->flash->success('Success! Please check your messages for an email reset password');
                        } else {
                            foreach ( $resetPassword->getMessages() as $message ) {

                                $this->flash->error($message);
                            }
                        }
                    }
                }
            }

            $this->view->form = $form;
        }


        public function resetPasswordAction()
        {
            $code = $this->dispatcher->getParam('code');

            $resetPassword = \Phalcon\UsersAuth\Models\ResetPasswords::findFirstByCode($code);

            if ( !$resetPassword ) {
                return $this->dispatcher->forward(
                    array(
                         'action' => 'index'
                    )
                );
            }

            if ( $resetPassword->reset <> 'N' ) {
                return $this->dispatcher->forward(
                    array(
                         'action' => 'login'
                    )
                );
            }

            $resetPassword->reset = 'Y';

            /**
             * Change the confirmation to 'reset'
             */
            if ( !$resetPassword->save() ) {

                foreach ( $resetPassword->getMessages() as $message ) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(
                    array(
                         'action' => 'index'
                    )
                );
            }

            /**
             * Identity the user in the application
             */
            $this->auth->authUserById($resetPassword->users_id);

            $this->flash->success('Please reset your password');

            return $this->dispatcher->forward(
                array(
                     'action' => 'changePassword'
                )
            );

        }


        /**
         * Users must use this action to change its password
         *
         */
        public function changePasswordAction()
        {
            $form = new \Phalcon\UsersAuth\Forms\ChangePasswordForm();

            if ( $this->request->isPost() ) {

                if ( !$form->isValid($this->request->getPost()) ) {

                    foreach ( $form->getMessages() as $message ) {
                        $this->flash->error($message);
                    }

                } else {

                    $user = $this->auth->getUser();

                    $user->password             = $this->security->hash($this->request->getPost('password'));
                    $user->must_change_password = 'N';

                    $passwordChange             = new \Phalcon\UsersAuth\Models\PasswordChanges();
                    $passwordChange->user       = $user;
                    $passwordChange->ip_address = $this->request->getClientAddress();
                    $passwordChange->user_agent = $this->request->getUserAgent();

                    if ( !$passwordChange->save() ) {
                        $this->flash->error($passwordChange->getMessages());
                    } else {

                        $this->flash->success('Your password was successfully changed');

                        \Phalcon\Tag::resetInput();
                    }

                }

            }

            $this->view->form = $form;
        }

        /**
         * User from the 'edit' action
         *
         * @return mixed
         */
        public function editProfileAction()
        {

            $user_id = $this->auth->getUser()->id;

            $user = \Phalcon\UsersAuth\Models\Users::findFirstById($user_id);

            if ( !$user ) {

                $this->flash->error("User was not found");

                return $this->dispatcher->forward(array( 'action' => 'index' ));
            }

            $form = new \Phalcon\UsersAuth\Forms\UsersForm($user , array( 'edit' => true ));

            if ( $this->request->isPost() ) {

                if ( $form->isValid($this->request->getPost()) != false ) {

                    $user->assign(
                        array(
                             'name'      => $this->request->getPost('name' , 'striptags') ,
                             'email'     => $this->request->getPost('email' , 'email') ,
                             'banned'    => $this->request->getPost('banned') ,
                             'suspended' => $this->request->getPost('suspended') ,
                        )
                    );

                    if ( !$user->save() ) {

                        $this->flash->error($user->getMessages());
                    } else {

                        $this->flash->success("User was updated successfully");

                        $this->tag->resetInput();
                    }
                }
            }

            $this->view->user = $user;

            $this->view->form = $form;
        }
    }
}

