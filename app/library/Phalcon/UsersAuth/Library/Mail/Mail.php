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
         * Applies a template to be used in the e-mail
         *
         * @param string $name
         * @param array  $params
         */
        public function getTemplate($name , $params)
        {

            $parameters = array_merge(
                array(
                     'publicUrl' => $this->config->application->siteUri ,
                ) ,
                $params
            );

            return $this->view->getRender(
                'emailTemplates' ,
                $name ,
                $parameters ,
                function ($view) {
                    $view->setRenderLevel(View::LEVEL_LAYOUT);
                }
            );
        }

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

            //Settings
            //$mailSettings = $this->config->mail;

            // Email template
            //$template = $this->getTemplate($name , $params);

            file_put_contents(ROOT . '/var/mails/mail-' . time() , sprintf("%s :: %s :: %s  :: (%s)" , json_encode($to) , $subject , $name , json_encode($params)));
        }
    }
}
