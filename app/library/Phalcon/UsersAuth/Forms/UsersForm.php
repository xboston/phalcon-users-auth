<?php

namespace Phalcon\UsersAuth\Forms {

    use Phalcon\Forms\Form , Phalcon\Forms\Element\Text , Phalcon\Forms\Element\Hidden , Phalcon\Forms\Element\Select , Phalcon\Forms\Element\Submit , Phalcon\Forms\Element\Check ,
        Phalcon\Validation\Validator\PresenceOf , Phalcon\Validation\Validator\Email , Phalcon\Validation\Validator\Identical , Phalcon\Validation\Validator\StringLength ,
        Phalcon\Validation\Validator\Confirmation;

    class UsersForm extends Form
    {

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

            //Banned
            $banned = new Check('banned' , [ 'value' => 'Y' ]);
            $banned->setLabel('User banned?');
            $this->add($banned);
            $this->add(new Hidden('banned-hidden' , [ 'name' => 'banned' , 'value' => 'N' ]));

            //Suspended
            $suspended = new Check('suspended' , [ 'value' => 'Y' ]);
            $suspended->setLabel('User suspended?');
            $this->add($suspended);
            $this->add(new Hidden('suspended-hidden' , [ 'name' => 'suspended' , 'value' => 'N' ]));


            //CSRF
            $csrf = new Hidden('csrf');
            $csrf->addValidator(
                new Identical([ 'value' => $this->security->getSessionToken() , 'message' => 'CSRF validation failed' ])
            );
            $this->add($csrf);

            //Sign Up
            $this->add(
                new Submit('save' , [ 'value' => 'Update' , 'class' => 'ui positive button' ])
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
