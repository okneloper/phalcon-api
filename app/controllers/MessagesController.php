<?php

/**
 * Created 17/11/2018
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 */
class MessagesController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $user = \App\Auth::getUser();

        //$messages = MessagesRepository::getInstance()->

        return [
            'data' => [
                []
            ],
        ];
    }
}
