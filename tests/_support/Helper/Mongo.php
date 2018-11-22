<?php

namespace Helper;

use MongoDB\Client;

/**
 */
class Mongo
{
    /**
     * @return \MongoDB\Database
     */
    public static function getDb()
    {
        return (new Client('mongodb://' . getenv('MONGODB_HOST')))->testing;
    }
}
