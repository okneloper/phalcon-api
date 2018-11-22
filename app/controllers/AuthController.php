<?php

/**
 * Created 17/11/2018
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 */
class AuthController extends \Phalcon\Mvc\Controller
{
    public function loginAction()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // @todo validate $username as an email

        if (!$username || !$password) {
            return $this->loginFailed();
        }

        $user = \App\Models\Repositories\UserRepository::getInstance()->findFirst([
            'username' => $username,
        ]);

        if ($user === null) {
            return $this->loginFailed();
        }
        /* @var $user \App\Models\User */

        if (!password_verify($password, $user->password)) {
            return $this->loginFailed();
        }

        $di = \Phalcon\Di::getDefault();

        $token = (new \Lcobucci\JWT\Builder())
            ->setIssuedAt(time()) // iat
            ->setSubject($user->username)
            // @todo fix the lifetime with refresh tokens
            ->setExpiration(time() + 3600) // exp
            ->sign($di->get(\Lcobucci\JWT\Signer::class), $di->get('config')->SECRET)
            ->getToken();

        //$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.9GVBtgLiP_dRWsux4gJexEa8hrTB7DtKypq5uTQER_o';
        return [
            'token' => (string)$token,
        ];
    }

    /**
     * @return array
     */
    protected function loginFailed(): array
    {
        $this->response->setStatusCode(401);
        $this->response->sendHeaders();
        return [
            'error' => "Invalid username or password",
        ];
    }
}
