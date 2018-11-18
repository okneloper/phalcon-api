<?php

namespace App;

use App\Middleware\Authenticate;
use Lcobucci\JWT\Signer;
use Okneloper\JwtValidators\SignatureValidator;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Mvc\Url as UrlProvider;

/**
 * Class Initializer
 */
class Initializer
{
    /**
     * @var \Phalcon\Mvc\Micro
     */
    private $app;

    /**
     * Initializer constructor.
     * @param \Phalcon\Mvc\Micro $app
     */
    public function __construct(\Phalcon\Mvc\Micro $app)
    {
        $this->app = $app;
    }

    public function initializeApplication()
    {
        $this->initDi();
        $this->initConfig($this->app->di);
        $this->initLoader();
        $this->initRoutes();
        $this->attachMiddleware();
    }

    /**
     * @param $app
     */
    protected function initRoutes(): void
    {
        $app = $this->app;

        // Auth handler
        $index = new MicroCollection();
        $index->setHandler(\AuthController::class, true);
        $index->get('/login', 'loginAction');
        $index->post('/login', 'loginAction');
        $app->mount($index);

        // Messages handler
        $index = new MicroCollection();
        $index->setHandler(\MessagesController::class, true);
        $index->setPrefix('/messages');
        $index->get('/', 'indexAction');
        $app->mount($index);

        // Index handler
        $index = new MicroCollection();
        $index->setHandler('IndexController', true);
        $index->get('/', 'indexAction');
        $app->mount($index);

        $app->notFound(function () use ($app) {
            $app->response->setStatusCode(404, 'Not Found');
            $app->response->sendHeaders();

            $message = 'Nothing to see here. Move along....';
            $app->response->setContent($message);
            $app->response->send();
        });
    }

    private function initDi()
    {
        // Create a DI
        $di = new FactoryDefault();

        // Setup a base URI
        $di->set(
            'url',
            function () {
                $url = new UrlProvider();
                $url->setBaseUri('/');
                return $url;
            }
        );

        $di->set(SignatureValidator::class, function () use ($di) {
            return new SignatureValidator($di->get('config')->SECRET,  new Signer\Hmac\Sha256());
        });
    }

    private function initLoader()
    {
        // Register an autoloader
        $loader = new Loader();

        $loader->registerDirs(
            [
                APP_PATH . '/controllers/',
                APP_PATH . '/models/',
            ]
        );

        $loader->register();
    }

    private function attachMiddleware()
    {
        $this->app->before(new Authenticate());

        $this->app->after(function () {
            // This is executed after the route is executed
            $returned_value = $this->app->getReturnedValue();
            if (is_array($returned_value)) {
                echo json_encode($returned_value);
            }
        });
    }

    private function initConfig(Di $di)
    {
        $di->set('config', function () {
            return new Config([
                'SECRET' => 'EAXrmVSJ3ONqz2YaUTFr8cVvd2dZpAB2jUZFNxti',
            ]);
        });
    }
}
