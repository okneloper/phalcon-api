<?php

use App\Models\Repositories\MessageRepository;

class MessageRepositoryTest extends \Codeception\Test\Unit
{
    use \Helper\SeedsDatabase;

    /**
     * @var MessageRepository
     */
    protected $repository;

    protected function _before()
    {
        $this->seedDatabase();

        $this->repository = \App\Models\Repositories\MessageRepository::getInstance();
    }

    protected function _after()
    {
        $this->cleanDatabase();
    }

    // tests
    public function testFindsMessagesByUser()
    {
        $user = \App\Models\Repositories\UserRepository::getInstance()->findByUsername('john@example.com');

        $messages = $this->repository->findByUser($user);

        $this->assertInstanceOf(\App\Collections\Collection::class, $messages);
    }
}
