<?php

/**
 * Example register form
 *
 */
namespace Phalcon\UsersAuth\Forms {

    use Phalcon\Forms\Form , Phalcon\Forms\Element\Text , Phalcon\Forms\Element\Hidden , Phalcon\Forms\Element\Password , Phalcon\Forms\Element\Submit , Phalcon\Forms\Element\Check ,
        Phalcon\Validation\Validator\PresenceOf , Phalcon\Validation\Validator\Email , Phalcon\Validation\Validator\Identical , Phalcon\Validation\Validator\StringLength ,
        Phalcon\Validation\Validator\Confirmation;

    class RegisterForm extends Form
    {

        /**
         *
         * @param null $entity
         * @param null $options
         */
        public function initialize($entity = null , $options = null)
        {

            $name = new Text('name');
            $name->setLabel('Name');
            $name->addValidators([ new PresenceOf([ 'message' => 'The name is required' ]) ]);
            $this->add($name);

            //Email
            $email = new Text('email');
            $email->setLabel('E-Mail');
            $email->addValidators(
                [
                new PresenceOf([ 'message' => 'The e-mail is required' ]) ,
                new Email([ 'message' => 'The e-mail is not valid' ])
                ]
            );
            $this->add($email);

            //Password
            $password = new Password('password');
            $password->setLabel('Password');
            $password->addValidators(
                [
                new PresenceOf([ 'message' => 'The password is required' ]) ,
                new StringLength([ 'min' => 8 , 'messageMinimum' => 'Password is too short. Minimum 8 characters' ]) ,
                new Confirmation([ 'message' => 'Password doesn\'t match confirmation' , 'with' => 'confirmPassword' ])
                ]
            );

            $this->add($password);

            //Confirm Password
            $confirmPassword = new Password('confirmPassword');
            $confirmPassword->setLabel('Confirm Password');
            $confirmPassword->addValidators(
                [
                new PresenceOf([ 'message' => 'The confirmation password is required' ])
                ]
            );
            $this->add($confirmPassword);

            //Remember
            $terms = new Check('terms' , [ 'value' => 'yes' ]);
            $terms->setLabel('I agree to the terms and conditions');
            $terms->addValidator(
                new Identical([ 'value' => 'yes' , 'message' => 'Terms and conditions must be accepted' ])
            );
            $this->add($terms);

            //CSRF
            $csrf = new Hidden('csrf');
            $csrf->addValidator(
                new Identical([ 'value' => $this->security->getSessionToken() , 'message' => 'CSRF validation failed' ])
            );
            $this->add($csrf);

            //Sign Up
            $this->add(
                new Submit('Sign Up' , [ 'class' => 'ui blue submit button' ])
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
