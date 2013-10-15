<?php

namespace Phalcon\UsersAuth\Models {

    use Phalcon\Mvc\Model;

    /**
     * SuccessLogins
     *
     * This model registers successfull logins registered users have made
     */
    class SuccessLogins extends Model
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
        public $ip_address;

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
            $this->belongsTo('users_ad' , 'Phalcon\UsersAuth\Models\Users' , 'id' , [ 'alias' => 'user' ]);
        }
    }
}
