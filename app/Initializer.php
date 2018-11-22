<?php

namespace App;

use App\Middleware\Authenticate;
use Lcobucci\JWT\Signer;
use MongoDB\Client;
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
        $this->initServices();
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
        $index->post('/', 'storeAction');
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
                'mongodb' => [
                    'host' => env('MONGODB_HOST', 'mongo'),
                    'db' => env('MONGODB_DB', 'test'),
                ],
            ]);
        });
    }

    private function initServices()
    {
        $di = $this->app->di;

        $config = $di->get('config');

        // Setup a base URI
        $di->set(
            'url',
            function () {
                $url = new UrlProvider();
                $url->setBaseUri('/');
                return $url;
            }
        );

        $di->set(Signer::class, function () {
            return new Signer\Hmac\Sha256();
        });

        $di->set(SignatureValidator::class, function () use ($di) {
            return new SignatureValidator($di->get('config')->SECRET,  $di->get(Signer::class));
        });

        $di->set('mongo', function () use ($config) {
            $db = $config->mongodb->db;
            return (new Client("mongodb://{$config->mongodb->host}"))->selectDatabase($db);
        });
    }
}
