<?php

namespace App\Models\Repositories;

use App\Models\Message;

/**
 */
class MessageRepository extends BaseRepository
{
    protected $table = 'messages';

    public function newModel($attributes)
    {
        return new Message($attributes);
    }

    /**
     * @param \App\Models\Model $user
     * @return array
     */
    public function findByUser(\App\Models\Model $user)
    {
        return $this->find([
            'user_id' => $user->_id,
        ]);
    }
}
