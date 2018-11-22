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

        $messages = \App\Models\Repositories\MessageRepository::getInstance()->findByUser($user);

        return [
            'data' => $messages,
        ];
    }
}
