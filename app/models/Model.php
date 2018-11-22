<?php

namespace App\Models;

use MongoDB\BSON\ObjectId;

/**
 * Base model class
 * @property null _id
 * @property string created_at
 * @property syring updated_at
 */
abstract class Model implements \JsonSerializable
{
    protected $attributes;

    /**
     * Model constructor.
     * @param $attributes
     */
    public function __construct($attributes)
    {
        if (isset($attributes['_id']) && $attributes['_id'] instanceof ObjectId) {
            $attributes['_id'] = (string)$attributes['_id'];
        }
        $this->attributes = $attributes;
    }

    public function __get($attr)
    {
        return $this->attributes[$attr] ?? null;
    }

    public function __set($attr, $value)
    {
        $this->attributes[$attr] = $value;
    }

    /**
     * Returns array representation of the object
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
