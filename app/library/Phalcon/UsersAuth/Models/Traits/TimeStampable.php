<?php
/**
 * Created by PhpStorm.
 * User: boston
 * Date: 15.10.13
 * Time: 17:38
 */

namespace Phalcon\UsersAuth\Models\Traits {


    trait TimeStampable
    {

        public function beforeCreate()
        {
            $this->created_at = date(DATE_ATOM);
        }

        public function beforeUpdate()
        {
            $this->modified_at = date(DATE_ATOM);
        }

    }
}
