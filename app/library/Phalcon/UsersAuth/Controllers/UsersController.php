<?php

/**
 *
 */
namespace Phalcon\UsersAuth\Controllers {

    use \Phalcon\Mvc\Controller , \Phalcon\UsersAuth\Forms\RegisterForm , \Phalcon\UsersAuth\Models\Users , \Phalcon\UsersAuth\Models\EmailConfirmations , \Phalcon\UsersAuth\Forms\LoginForm ,
        \Phalcon\UsersAuth\Forms\ForgotPasswordForm , \Phalcon\UsersAuth\Library\Auth\Exception\Exception , \Phalcon\UsersAuth\Models\ResetPasswords , \Phalcon\UsersAuth\Forms\ChangePasswordForm ,
        \Phalcon\UsersAuth\Models\PasswordChanges , \Phalcon\UsersAuth\Forms\UsersForm;

    /**
     * Example UsersController class
     *
     * @property \Phalcon\UsersAuth\Library\Auth\Auth $auth
     */
    class UsersController extends Controller
    {

        /**
         *
         */
        public function indexAction()
        {

        }

        /**
         * Step 1 - register
         *
         * @return mixed
         */
        public function registerAction()
        {

            $form = new RegisterForm();

            if ( $this->request->isPost() ) {

                if ( $form->isValid($this->request->getPost()) != false ) {

                    $user = new Users();

                    $user->assign(
                        [
                        'name'     => $this->request->getPost('name' , 'striptags') ,
                        'email'    => $this->request->getPost('email') ,
                        'password' => $this->security->hash($this->request->getPost('password')) ,
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

            $confirmation = EmailConfirmations::findFirstByCode($code);

            if ( !$confirmation ) {

                $this->flash->error('Code not found');
                return $this->response->redirect(['for'=>'login']);
            }

            $email = $this->dispatcher->getParam('email');
            if ( $confirmation->user->email != $email ) {

                $this->flash->error('Email not found');
                return $this->response->redirect(['for'=>'login']);
            }


            if ( $confirmation->confirmed <> Users::FALSE ) {

                return $this->response->redirect(['for'=>'login']);
            }

            $confirmation->confirmed = Users::TRUE;

            $confirmation->user->activated = Users::TRUE;

            /**
             * Change the confirmation to 'confirmed' and update the user to 'activated'
             */
            if ( !$confirmation->save() ) {

                foreach ( $confirmation->getMessages() as $message ) {
                    $this->flash->error($message);
                }

                return $this->response->redirect(['for'=>'index']);
            }

            /**
             * Identity the user in the application
             */
            $this->auth->authUserById($confirmation->user->id);

            /**
             * Check if the user must change his/her password
             */
            if ( $confirmation->user->must_change_password == Users::TRUE ) {

                $this->flash->success('The email was successfully confirmed. Now you must change your password');

                return $this->response->redirect(['for'=>'change-password']);
            }

            $this->flash->success('The email was successfully confirmed');

            return $this->response->redirect(['for'=>'index']);
        }

        /**
         * Step 3 - login
         *
         * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
         */
        public function loginAction()
        {
            $form = new LoginForm();

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

                        return $this->response->redirect();
                    }
                }

            } catch ( Exception $e ) {

                $this->flash->error($e->getMessage());
            }

            $this->view->form = $form;
        }

        /**
         * Step 4 - logout
         *
         * @return \Phalcon\Http\ResponseInterface
         */
        public function logoutAction()
        {

            $this->auth->remove();

            return $this->response->redirect($this->url->get([ 'for' => 'index' ]));
        }

        /**
         * Step 5 - forgot password
         *
         */
        public function forgotAction()
        {

            $form = new ForgotPasswordForm();

            if ( $this->request->isPost() ) {

                if ( $form->isValid($this->request->getPost()) == false ) {

                    foreach ( $form->getMessages() as $message ) {
                        $this->flash->error($message);
                    }
                } else {

                    $user = Users::findFirstByEmail($this->request->getPost('email'));
                    if ( !$user ) {
                        $this->flash->notice('There is no account associated to this email');
                    } else {

                        $resetPassword           = new ResetPasswords();
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

        /**
         * Step 6 - reset password
         *
         * @return mixed
         */
        public function resetPasswordAction()
        {
            $code = $this->dispatcher->getParam('code');

            $resetPassword = ResetPasswords::findFirstByCode($code);

            if ( !$resetPassword ) {
                return $this->dispatcher->forward(
                    array(
                         'action' => 'index'
                    )
                );
            }

            if ( $resetPassword->reset <> Users::FALSE ) {
                return $this->dispatcher->forward(
                    array(
                         'action' => 'login'
                    )
                );
            }

            $resetPassword->reset = Users::TRUE;

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
         * Step 7 - change password
         *
         * Users must use this action to change its password
         *
         */
        public function changePasswordAction()
        {
            $form = new ChangePasswordForm();

            if ( $this->request->isPost() ) {

                if ( !$form->isValid($this->request->getPost()) ) {

                    foreach ( $form->getMessages() as $message ) {
                        $this->flash->error($message);
                    }

                } else {

                    $user = $this->auth->getUser();

                    $user->password             = $this->security->hash($this->request->getPost('password'));
                    $user->must_change_password = Users::FALSE;

                    $passwordChange             = new PasswordChanges();
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
         * Step 7 - edit user data
         * User from the 'edit' action
         *
         * @return mixed
         */
        public function editProfileAction()
        {

            $user_id = $this->auth->getUser()->id;

            $user = Users::findFirstById($user_id);

            if ( !$user ) {

                $this->flash->error("User was not found");

                return $this->dispatcher->forward(array( 'action' => 'index' ));
            }

            $form = new UsersForm($user , array( 'edit' => true ));

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
