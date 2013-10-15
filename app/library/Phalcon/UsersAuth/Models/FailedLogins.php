<?php

namespace Phalcon\UsersAuth\Models {

    use Phalcon\Mvc\Model;

    /**
     * FailedLogins
     *
     * This model registers unsuccessfull logins registered and non-registered users have made
     */
    class FailedLogins extends Model
    {

        use \Phalcon\UsersAuth\Models\Traits\Timestampable;

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
         * @var integer
         */
        public $attempted;

        /**
         * @var string
         */
        public $created_at;

        public function initialize()
        {
            $this->belongsTo('users_id' , 'Phalcon\UsersAuth\Models\Users' , 'id' , [ 'alias' => 'user' ]);
        }
    }
}
