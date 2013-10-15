<?php

namespace Phalcon\UsersAuth\Models {

    use Phalcon\Mvc\Model;

    /**
     * EmailConfirmations
     *
     * Stores the reset password codes and their evolution
     */
    class EmailConfirmations extends Model
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
        public $confirmed;

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
            $this->belongsTo(
                'users_id' ,
                'Phalcon\UsersAuth\Models\Users' ,
                'id' ,
                [ 'alias' => 'user' ]
            );
        }

        /**
         * Before create the user assign a password
         */
        public function beforeValidationOnCreate()
        {

            //Generate a random confirmation code
            // @todo - использовать нативный генератор
            $this->code = preg_replace('/[^a-zA-Z0-9]/' , '' , base64_encode(openssl_random_pseudo_bytes(24)));

            //Set status to non-confirmed
            $this->confirmed = Users::FALSE;
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
         * Send a confirmation e-mail to the user after create the account
         */
        public function afterCreate()
        {
            $this->getDI()->getMail()->send(
                [ $this->user->email => $this->user->name ] ,
                "Please confirm your email" ,
                'confirmation' ,
                [ 'confirmUrl' => $this->getDI()->getUrl()->get([ 'for' => 'confirm-email' , 'code' => $this->code , 'email' => $this->user->email ]) ]
            );
        }
    }
}
