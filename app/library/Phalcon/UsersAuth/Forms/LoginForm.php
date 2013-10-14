<?php

namespace Phalcon\UsersAuth\Forms {

    use Phalcon\Forms\Form , Phalcon\Forms\Element\Text , Phalcon\Forms\Element\Password , Phalcon\Forms\Element\Submit , Phalcon\Forms\Element\Check , Phalcon\Forms\Element\Hidden ,
        Phalcon\Validation\Validator\PresenceOf , Phalcon\Validation\Validator\Email , Phalcon\Validation\Validator\Identical;

    class LoginForm extends Form
    {

        public function initialize()
        {
            //Email
            $email = new Text('email' , [ 'placeholder' => 'Email' ]);
            $email->setLabel('Email');
            $email->addValidators(
                [
                new PresenceOf([ 'message' => 'The e-mail is required' ]) ,
                new Email([ 'message' => 'The e-mail is not valid' ])
                ]
            );
            $this->add($email);

            //Password
            $password = new Password('password' , [ 'placeholder' => 'Password' ]);
            $password->setLabel('Password');
            $password->addValidator(
                new PresenceOf([ 'message' => 'The password is required' ])
            );
            $this->add($password);

            //Remember
            $remember = new Check('remember' , [ 'value' => 'yes' ]);
            $remember->setLabel('Remember me');
            $this->add($remember);

            //CSRF
            $csrf = new Hidden('csrf');
            $csrf->addValidator(
                new Identical([ 'value' => $this->security->getSessionToken() , 'message' => 'CSRF validation failed' ])
            );
            $this->add($csrf);

            $this->add(
                new Submit('Login' , [ 'class' => 'ui blue submit button' ])
            );
        }

        /**
         * Prints messages for a specific element
         *
         */
        public function messages($name)
        {
            if ( $this->hasMessagesFor($name) ) {
                foreach ( $this->getMessagesFor($name) as $message ) {
                    $this->flash->error($message);
                }
            }
        }
    }
}
