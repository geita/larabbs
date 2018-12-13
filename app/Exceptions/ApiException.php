<?php

namespace App\Exceptions;

/**
 * 
 */
class ApiException extends Exceptions
{
    private static $errors = [
        10000 => 'this is a api test!'
    ];

    public function __construct($code, $message = '')
    {
        if (empty($message)) {
            $message = self::$errors[$message];
        }

        parent::__construct($message, $code);
    }
}