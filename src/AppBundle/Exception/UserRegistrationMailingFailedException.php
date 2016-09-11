<?php

namespace AppBundle\Exception;

/**
 * Class UserRegistrationMailingFailedException
 */
class UserRegistrationMailingFailedException extends BasicException
{
    protected $message = 'User registration mailing failed.';
}
