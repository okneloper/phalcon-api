<?php

/**
 * Created 17/11/2018
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 */
class AuthController extends \Phalcon\Mvc\Controller
{
    public function loginAction()
    {
        return $this->response->setJsonContent([
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.9GVBtgLiP_dRWsux4gJexEa8hrTB7DtKypq5uTQER_o',
        ]);
    }
}
