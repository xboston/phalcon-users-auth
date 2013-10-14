<?php

class ErrorController extends ControllerBase
{

    public function error404Action()
    {
        $this->response->setStatusCode(404, 'Not found');
    }

}