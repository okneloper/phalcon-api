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

    public function storeAction()
    {
        $text = trim($this->request->getPost('text'));

        if (empty($text)) {
            $this->response->setStatusCode(422);
            $this->response->sendHeaders();

            return [
                'errors' => [
                    'text' => "Text can not be empty",
                ]
            ];
        }

        $message = new \App\Models\Message([
            'text' => $text,
            'user_id' => \App\Auth::getUser()->_id,
        ]);

        $message = \App\Models\Repositories\MessageRepository::getInstance()->store($message);

        return [
            'result' => 'ok',
            'message' => $message,
        ];
    }
}
