<?php

namespace App\Models\Repositories;

use App\Models\Model;
use App\Models\User;

/**
 * Created 19/11/2018
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 */
class UserRepository extends BaseRepository
{
    public function newModel($attributes)
    {
        return new User($attributes);
    }
}
