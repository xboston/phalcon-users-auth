<?php

namespace Phalcon\UsersAuth\Models {

    use Phalcon\Mvc\Model;

    /**
     * ResetPasswords
     *
     * Stores the reset password codes and their evolution
     *
     * @property \Phalcon\UsersAuth\Models\Users $user
     */
    class ResetPasswords extends Model
    {
        use \Phalcon\UsersAuth\Models\Traits\TimeStampable;

        /**
         * @var integer
         */
        public $id;

        /**
         * @var integer
         */
        public $users_id;

        /**
         * @var string
         */
        public $code;

        /**
         * @var string
         */
        public $reset;

        /**
         * @var string
         */
        public $created_at;

        /**
         * @var string
         */
        public $modified_at;

        public function initialize()
        {
            $this->belongsTo('users_id' , 'Phalcon\UsersAuth\Models\Users' , 'id' , [ 'alias' => 'user' ]);
        }

        /**
         * Before create the user assign a password
         */
        public function beforeValidationOnCreate()
        {

            //Generate a random confirmation code
            // @todo использовать нативный генератор
            $this->code = preg_replace('/[^a-zA-Z0-9]/' , '' , base64_encode(openssl_random_pseudo_bytes(24)));

            //Set status to non-confirmed
            $this->reset = Users::FALSE;
        }

        /**
         * Sets the timestamp before update the confirmation
         */
        public function beforeValidationOnUpdate()
        {
            //Timestamp the confirmaton
            $this->modified_at = time();
        }

        /**
         * Send an e-mail to users allowing him/her to reset his/her password
         */
        public function afterCreate()
        {
            $this->getDI()->getMail()->send(
                [ $this->user->email => $this->user->name ] ,
                "Reset your password" ,
                'reset' ,
                [ 'resetUrl' => '/reset-password/' . $this->code . '/' . $this->user->email ]
            );
        }
    }
}
