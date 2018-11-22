<?php

namespace App\Models\Repositories;

use App\Models\User;

/**
 * Created 19/11/2018
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 */
class UserRepository extends BaseRepository
{
    protected $table = 'users';

    public function newModel($attributes)
    {
        return new User($attributes);
    }
}
