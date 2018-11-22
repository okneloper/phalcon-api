<?php

namespace Helper;

/**
 */
trait SeedsDatabase
{
    protected $mongo;

    public function seedDatabase()
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

    public function cleanDatabase()
    {
        $this->mongo->users->deleteMany([]);
    }
}
