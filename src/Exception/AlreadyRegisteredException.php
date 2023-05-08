<?php

namespace App\Exception;

class AlreadyRegisteredException extends \Exception
{
    public function __construct(string $nick)
    {
        parent::__construct("User {$nick} is already registered.");
    }
}
