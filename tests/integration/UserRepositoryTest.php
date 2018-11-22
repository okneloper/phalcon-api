<?php

class UserRepositoryTest extends \Codeception\Test\Unit
{
    protected $mongo;

    protected function _before()
    {
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
        $repository = \App\Models\Repositories\UserRepository::getInstance();

        $users = $repository->find([
            'username' => 'user@domain.com',
        ]);

        $this->assertNotNull($users);
        $this->assertInternalType('array', $users);
        $this->assertCount(1, $users);
        $this->assertInstanceOf(\App\Models\Model::class, $users[0]);
    }

    public function testFindsFirst()
    {
        $repository = \App\Models\Repositories\UserRepository::getInstance();

        $user = $repository->findFirst([
            'type' => 'guest',
        ]);

        $this->assertInstanceOf(\App\Models\User::class, $user);

        $this->assertEquals('guest', $user->type);
    }

    public function testFindFirstReturnsNullOnNoResult()
    {
        $repository = \App\Models\Repositories\UserRepository::getInstance();

        $user = $repository->findFirst([
            'type' => 'god',
        ]);

        $this->assertNull($user);
    }
}
