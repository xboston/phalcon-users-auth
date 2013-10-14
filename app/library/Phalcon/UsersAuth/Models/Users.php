<?php

namespace Phalcon\UsersAuth\Models {

    use Phalcon\Mvc\Model , Phalcon\Mvc\Model\Validator\Uniqueness;

    /**
     * Phalcon\UsersAuth\Models\Users
     *
     * All the users registered in the application
     */
    class Users extends Model
    {

        const TRUE = 'Y';
        const FALSE = 'N';


        /**
         * @var integer
         */
        public $id;

        /**
         * @var string
         */
        public $name;

        /**
         * @var string
         */
        public $email;

        /**
         * @var string
         */
        public $password;

        /**
         * @var string
         */
        public $must_change_password;

        /**
         * @var string
         */
        public $banned;

        /**
         * @var string
         */
        public $suspended;

        /**
         * @var string
         */
        public $active;


        public function initialize()
        {

            $this->hasMany(
                'id' ,
                'Phalcon\UsersAuth\Models\SuccessLogins' ,
                'users_id' ,
                [
                'alias'      => 'successLogins' ,
                'foreignKey' => [
                    'message' => 'User cannot be deleted because he/she has activity in the system'
                ]
                ]
            );

            $this->hasMany(
                'id' ,
                'Phalcon\UsersAuth\Models\PasswordChanges' ,
                'users_id' ,
                [
                'alias'      => 'passwordChanges' ,
                'foreignKey' => [
                    'message' => 'User cannot be deleted because he/she has activity in the system'
                ]
                ]
            );

            $this->hasMany(
                'id' ,
                'Phalcon\UsersAuth\Models\ResetPasswords' ,
                'users_id' ,
                [
                'alias'      => 'resetPasswords' ,
                'foreignKey' => [
                    'message' => 'User cannot be deleted because he/she has activity in the system'
                ]
                ]
            );
        }


        /**
         * Before create the user assign a password
         */
        public function beforeValidationOnCreate()
        {
            if ( empty($this->password) ) {

                //Generate a plain temporary password
                // @todo использорвать нативный генератор
                $tempPassword = preg_replace('/[^a-zA-Z0-9]/' , '' , base64_encode(openssl_random_pseudo_bytes(12)));

                //The user must change its password in first login
                $this->must_change_password = Users::TRUE;

                //Use this password as default
                $this->password = $this->getDI()->getSecurity()->hash($tempPassword);

            } else {

                //The user must not change its password in first login
                $this->must_change_password = Users::FALSE;
            }

            //The account must be confirmed via e-mail
            $this->active = Users::FALSE;

            //The account is not suspended by default
            $this->suspended = Users::FALSE;

            //The account is not banned by default
            $this->banned = Users::FALSE;
        }

        /**
         * Send a confirmation e-mail to the user if the account is not active
         */
        public function afterSave()
        {
            if ( $this->active == Users::FALSE ) {

                $emailConfirmation = new EmailConfirmations();

                $emailConfirmation->users_id = $this->id;

                if ( $emailConfirmation->save() ) {
                    $this->getDI()->getFlash()->notice(
                        'A confirmation mail has been sent to ' . $this->email
                    );
                }
            }
        }

        /**
         * Validate that emails are unique across users
         */
        public function validation()
        {

            $this->validate(
                new Uniqueness([
                               "field"   => "email" ,
                               "message" => "The email is already registered"
                               ])
            );

            return $this->validationHasFailed() != true;
        }
    }
}
