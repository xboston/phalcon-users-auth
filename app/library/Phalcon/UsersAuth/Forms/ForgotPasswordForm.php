<?php

namespace Phalcon\UsersAuth\Forms {

    use Phalcon\Forms\Form , Phalcon\Forms\Element\Text , Phalcon\Forms\Element\Hidden ,  Phalcon\Validation\Validator\Identical , Phalcon\Forms\Element\Submit , Phalcon\Validation\Validator\PresenceOf , Phalcon\Validation\Validator\Email;

    class ForgotPasswordForm extends Form
    {

        public function initialize()
        {

            $email = new Text('email' , [ 'placeholder' => 'Email' ]);
            $email->setLabel('email');
            $email->addValidators(
                array(
                     new PresenceOf([ 'message' => 'The e-mail is required' ]) ,
                     new Email([ 'message' => 'The e-mail is not valid' ])
                )
            );
            $this->add($email);

            $this->add(
                new Submit('Send' , [ 'class' => 'ui blue submit button' ])
            );

            //CSRF
            $csrf = new Hidden('csrf');
            $csrf->addValidator(
                new Identical([ 'value' => $this->security->getSessionToken() , 'message' => 'CSRF validation failed' ])
            );
            $this->add($csrf);
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
