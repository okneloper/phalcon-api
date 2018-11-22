<?php

namespace App\Models;

use MongoDB\BSON\ObjectId;

/**
 * Base model class
 */
abstract class Model
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
}
