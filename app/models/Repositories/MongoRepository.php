<?php

namespace App\Models\Repositories;

use App\Models\Model;
use MongoDB\Client;
use MongoDB\Collection;

/**
 * Created 19/11/2018
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 */
abstract class MongoRepository implements Repository
{
    abstract public function newModel($attributes);

    protected $collection;

    protected $model_class;

    /**
     * MongoRepository constructor.
     * @param $collection
     */
    public function __construct(Collection $collection)
    {
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
            $models[] = $this->newModel($result->getArrayCopy());
        }

        return $models;
    }

    /**
     * Returns first model matching the conditions
     * @param null $parameters
     * @return mixed
     */
    public function findFirst($parameters = null):Model
    {
        $parameters = (array)$parameters;

        $result = $this->collection->findOne($parameters)->getArrayCopy();

        return $this->newModel($result);
    }
}
