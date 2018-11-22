<?php

namespace App;

use App\Models\User;

/**
 */
class Auth
{
    /**
     * @var User
     */
    protected static $user;

    /**
     * @return User
     */
    public static function getUser(): User
    {
        if (!static::$user) {
            throw new \BadMethodCallException("User is not authenticated");
        }
        return self::$user;
    }

    /**
     * @param User $user
     */
    public static function setUser(User $user): void
    {
        self::$user = $user;
    }
}
