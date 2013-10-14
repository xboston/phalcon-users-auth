<?php

namespace Phalcon\UsersAuth\Library\Mail {

    use Phalcon\Mvc\User\Component , Phalcon\Mvc\View;


    /**
     * Phalcon\UsersAuth\Mail\Mail
     *
     * Sends e-mails based on pre-defined templates
     */
    class Mail extends Component
    {


        /**
         * Sends e-mails
         *
         * @param array  $to
         * @param string $subject
         * @param string $name
         * @param array  $params
         */
        public function send($to , $subject , $name , $params)
        {

            file_put_contents(PUBLICROOT . '/var/mails/mail-' . time() , sprintf("%s :: %s :: %s  :: (%s)" , json_encode($to) , $subject , $name , json_encode($params)));
        }
    }
}
