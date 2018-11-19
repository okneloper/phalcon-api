<?php

namespace App\Models;

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
