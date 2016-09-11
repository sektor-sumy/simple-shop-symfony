<?php

namespace AppBundle\Exception;

/**
 * Class UserNotFoundException
 */
class UserNotFoundException extends BasicException
{
    protected $message = 'User not found.';
}
