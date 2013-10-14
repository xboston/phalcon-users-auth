<?php

namespace Phalcon\UsersAuth\Models {

    use Phalcon\Mvc\Model;

    /**
     * PasswordChanges
     *
     * Register when a user changes his/her password
     */
    class PasswordChanges extends Model
    {
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
        public $ip_address;

        /**
         * @var string
         */
        public $user_agent;

        /**
         * @var integer
         */
        public $created_at;

        public function initialize()
        {
            $this->belongsTo('users_id' , 'Phalcon\UsersAuth\Models\Users' , 'id' , [ 'alias' => 'user' ]);
        }

        /**
         * Before create the user assign a password
         */
        public function beforeValidationOnCreate()
        {
            //Timestamp the confirmaton
            $this->created_at = time();
        }
    }
}
