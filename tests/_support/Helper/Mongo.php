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
        return (new Client())->test;
    }
}
