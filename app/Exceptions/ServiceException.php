<?php

namespace App\Exception;

/**
 * 
 */
class ServerException extends \Exception
{
    
    private static $error = [
        10000 => 'this is a test!'
    ];

    public function __construct($code, $message = '')
    {
        if (empty($message)) {
            $message = self::$errors[$code];
        }

        parent::__construct($message, $code);
    }
}