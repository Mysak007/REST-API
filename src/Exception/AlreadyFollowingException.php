<?php

namespace App\Exception;

class AlreadyFollowingException extends \Exception
{
    public function __construct(string $target, string $follower)
    {
        parent::__construct("User {$target} already following {$follower}");
    }
}
