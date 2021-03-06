<?php

namespace App\Models\Repositories;

use App\Collections\Collection;
use App\Models\Model;
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
    public function find(array $parameters = null): Collection
    {
        $parameters = (array)$parameters;

        $models = [];

        foreach ($this->collection->find($parameters) as $result) {
            $models[] = $this->newModel($result->getArrayCopy());
        }

        return new Collection($models);
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

    /**
     * @param Model $model
     * @return Model
     */
    public function store(Model $model): Model
    {
        $now = date('Y-m-d H:i:s');

        if (!isset($model->_id)) {
            $model->created_at = $now;
        }
        $model->updated_at = $now;

        $result = $this->collection->insertOne($model->toArray());

        if ($new_id = $result->getInsertedId()) {
            $model->_id = $new_id;
        }

        return $model;
    }
}
