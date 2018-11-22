<?php

use App\Models\Repositories\UserRepository;

class UserRepositoryTest extends \Codeception\Test\Unit
{
    use \Helper\SeedsDatabase;

    /**
     * @var UserRepository
     */
    protected $repository;

    protected function _before()
    {
        $this->seedDatabase();

        $this->repository = \App\Models\Repositories\UserRepository::getInstance();
    }

    protected function _after()
    {
        $this->cleanDatabase();
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
