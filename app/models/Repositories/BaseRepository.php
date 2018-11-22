<?php

namespace App\Models\Repositories;

use MongoDB\Collection;
use Phalcon\Di;

/**
 * Base repository class
 */
abstract class BaseRepository extends MongoRepository
{
    /**
     * Returns a new instance of the repository
     * @return static
     */
    public static function getInstance()
    {
        $mongo = Di::getDefault()->get('mongo');

        return new static($mongo);
    }
}
