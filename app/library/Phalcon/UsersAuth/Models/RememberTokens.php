<?php

namespace Phalcon\UsersAuth\Models {

    use Phalcon\Mvc\Model;

    /**
     * RememberTokens
     *
     * Stores the remember me tokens
     */
    class RememberTokens extends Model
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
        public $token;

        /**
         * @var string
         */
        public $user_agent;

        /**
         * @var integer
         */
        public $created_at;

        /**
         * Before create the user assign a password
         */
        public function beforeValidationOnCreate()
        {
            //Timestamp the confirmaton
            $this->created_at = time();
        }

        public function initialize()
        {
            $this->belongsTo(
                'users_id' ,
                'Phalcon\UsersAuth\Models\Users' ,
                'id' ,
                [ 'alias' => 'user' ]
            );
        }
    }
}
