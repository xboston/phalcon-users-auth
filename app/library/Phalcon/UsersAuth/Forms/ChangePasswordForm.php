<?php

namespace Phalcon\UsersAuth\Forms {

    use Phalcon\Forms\Form , Phalcon\Forms\Element\Password , Phalcon\Validation\Validator\PresenceOf , Phalcon\Validation\Validator\StringLength , Phalcon\Validation\Validator\Confirmation;


    class ChangePasswordForm extends Form
    {

        public function initialize()
        {
            //Password
            $password = new Password('password');

            $password->addValidators(
                array(
                     new PresenceOf([ 'message' => 'Password is required' ]) ,
                     new StringLength([ 'min' => 8 , 'messageMinimum' => 'Password is too short. Minimum 8 characters' ]) ,
                     new Confirmation([ 'message' => 'Password doesn\'t match confirmation' , 'with' => 'confirmPassword' ])
                )
            );

            $this->add($password);

            //Confirm Password
            $confirmPassword = new Password('confirmPassword');

            $confirmPassword->addValidators(
                array(
                     new PresenceOf([
                                    'message' => 'The confirmation password is required'
                                    ])
                )
            );

            $this->add($confirmPassword);
        }
    }
}
