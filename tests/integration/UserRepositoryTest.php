<?php

use App\Models\Repositories\UserRepository;

class UserRepositoryTest extends \Codeception\Test\Unit
{
    protected $mongo;

    /**
     * @var UserRepository
     */
    protected $repository;

    protected function _before()
    {
        $this->repository = \App\Models\Repositories\UserRepository::getInstance();

        $this->mongo = \Phalcon\Di::getDefault()->get('mongo');

        $this->mongo->users->insertMany([
            [
                'username' => 'camilla@example.com',
                'name' => 'Camilla Kacey',
                'type' => 'admin',
            ],
            [
                'username' => 'john@example.com',
                'name' => 'John Allen',
                'type' => 'guest',
            ],
            [
                'username' => 'user@domain.com',
                'name' => 'Grenville Rowland',
                'type' => 'guest',
            ]
        ]);
    }

    protected function _after()
    {
        $this->mongo->users->deleteMany([]);
    }


    public function testFinds()
    {
        $users = $this->repository->find([
            'username' => 'user@domain.com',
        ]);

        $this->assertNotNull($users);
        $this->assertInstanceOf(\App\Collections\Collection::class, $users);
        $this->assertCount(1, $users);
        $this->assertInstanceOf(\App\Models\Model::class, $users[0]);
    }

    public function testFindsFirst()
    {
        $user = $this->repository->findFirst([
            'type' => 'guest',
        ]);

        $this->assertInstanceOf(\App\Models\User::class, $user);

        $this->assertEquals('guest', $user->type);
    }

    public function testFindFirstReturnsNullOnNoResult()
    {
        $user = $this->repository->findFirst([
            'type' => 'god',
        ]);

        $this->assertNull($user);
    }

    public function testFindsByEmail()
    {
        $user = $this->repository->findByUsername('john@example.com');

        $this->assertInstanceOf(\App\Models\User::class, $user);
        $this->assertEquals('john@example.com', $user->username);
    }
}
