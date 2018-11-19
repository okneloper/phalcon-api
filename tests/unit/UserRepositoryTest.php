<?php

use App\Models\Repositories\MongoRepository;

class UserRepositoryTest extends \Codeception\Test\Unit
{
    protected $mongo;

    protected function _before()
    {
        $this->mongo = \Helper\Mongo::getDb();

        $this->mongo->users->insertMany([
            [
                'username' => 'user@example.com',
                'name' => 'Camilla Kacey',
            ],
            [
                'username' => 'user@domain.com',
                'name' => 'Grenville Rowland',
            ]
        ]);
    }

    protected function _after()
    {
        $this->mongo->users->deleteMany([]);
    }


    public function testFinds()
    {
        $repository = new App\Models\Repositories\UserRepository($this->mongo->users);

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

    }
}
