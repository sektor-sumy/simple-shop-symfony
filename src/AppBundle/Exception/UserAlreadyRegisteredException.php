<?php

namespace AppBundle\Exception;

/**
 * Class UserAlreadyRegisteredException
 */
class UserAlreadyRegisteredException extends BasicException
{
    protected $message = 'User already registered.';
}
