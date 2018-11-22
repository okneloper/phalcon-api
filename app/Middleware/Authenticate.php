<?php

namespace App\Middleware;

use Lcobucci\JWT\Parser;
use Okneloper\JwtValidators\SignatureValidator;
use Okneloper\JwtValidators\Validator;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;

/**
 * Authenticate middleware
 * Makes sure API can only be accessed with a valid token
 */
class Authenticate implements \Phalcon\Mvc\Micro\MiddlewareInterface
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * Calls the middleware
     *
     * @param \Phalcon\Mvc\Micro $application
     */
    public function call(\Phalcon\Mvc\Micro $application)
    {
        $this->response = $application->response;

        if ($this->resourceIsUnrestricted($application)) {
            return true;
        }

        return $this->clientIsAuthorized($application);
    }

    /**
     * @param \Phalcon\Mvc\Micro $application
     * @return bool
     */
    protected function unauthorized($error = null): bool
    {
        $response = $this->response;
        $response->setStatusCode(401);
        $response->sendHeaders();

        if ($error) {
            $response->setJsonContent(compact('error'));
        }
        $response->send();

        return false;
    }

    private function resourceIsUnrestricted(Micro $application)
    {
        $unrestricted = [
            \IndexController::class,
            \AuthController::class,
        ];

        $controller_loader = $application->getActiveHandler()[0];
        /* @var $controller_loader LazyLoader */

        if (in_array($controller_loader->getDefinition(), $unrestricted)) {
            // this resource is unrestricted
            return true;
        }
    }

    private function clientIsAuthorized(Micro $application)
    {
        if (!$application->request->hasHeader('Authorization')) {
            return $this->unauthorized();
        }

        $header_value = $application->request->getHeader('Authorization');

        if (strpos($header_value, 'Bearer ') !== 0) {
            return $this->unauthorized();
        }

        // get token from the header
        $token = substr($header_value, strlen('Bearer '));

        // validate token format
        try {
            $token = (new Parser())->parse($token);
        } catch (\InvalidArgumentException $e) {
            return $this->unauthorized();
        }

        $validator = new Validator([
            $application->di->get(SignatureValidator::class),
            // @todo also validate the expiration
            // new ExpirationValidator(),
        ]);

        if (!$validator->validates($token)) {
            $message = $validator->getErrors()[0];
            return $this->unauthorized($message);
        }

        return true;
    }
}
