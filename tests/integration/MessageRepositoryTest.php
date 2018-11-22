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

    public function testStoresAMessage()
    {
        $user = \App\Models\Repositories\UserRepository::getInstance()->findByUsername('john@example.com');
        $message = new \App\Models\Message([
            'user_id' => $user->_id,
            'text' => 'Hello!'
        ]);

        $id = MessageRepository::getInstance()->store($message);

        $found_in_db = $this->mongo->messages->findOne([
            '_id' => new \MongoDB\BSON\ObjectId($id),
        ]);

        $this->assertNotNull($found_in_db);
        $this->assertEquals('Hello!', $found_in_db->text);
    }

}
