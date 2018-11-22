<?php

namespace App\Models\Repositories;

use App\Models\Model;

/**
 */
interface Repository
{
    /**
     * Find models by conditions
     * @param array|null $parameters
     * @return array
     */
    public function find(array $parameters = null): array;

    /**
     * Returns first model matching the conditions
     * @param null $parameters
     * @return mixed
     */
    public function findFirst($parameters = null): ?Model;
}
