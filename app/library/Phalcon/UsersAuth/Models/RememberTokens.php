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
        public $token;

        /**
         * @var string
         */
        public $user_agent;

        /**
         * @var string
         */
        public $created_at;

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
