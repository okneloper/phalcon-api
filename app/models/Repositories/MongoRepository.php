<?php

namespace App\Models\Repositories;

use App\Models\Model;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;

/**
 * Created 19/11/2018
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 */
abstract class MongoRepository implements Repository
{
    abstract public function newModel($attributes);

    /**
     * MongoDB Collection name
     * @var string
     */
    protected $table;

    /**
     * MongoDB collection instance
     * @var Collection
     */
    protected $collection;

    /**
     * MongoRepository constructor.
     * @param $collection
     */
    public function __construct(Database $db)
    {
        if (!$this->table) {
            throw new \BadMethodCallException('Collection name $this->table is not set');
        }
        $collection = $db->{$this->table};
        $this->collection = $collection;
    }

    /**
     * Find models by conditions
     * @param array|null $parameters
     * @return array
     */
    public function find(array $parameters = null):array
    {
        $parameters = (array)$parameters;

        $models = [];

        foreach ($this->collection->find($parameters) as $result) {
            d($result);
            $models[] = $this->newModel($result->getArrayCopy());
        }

        return $models;
    }

    /**
     * Returns first model matching the conditions
     * @param null $parameters
     * @return mixed
     */
    public function findFirst($parameters = null): ?Model
    {
        $parameters = (array)$parameters;

        $result = $this->collection->findOne($parameters);

        if ($result === null) {
            return null;
        }

        return $this->newModel($result->getArrayCopy());
    }
}
