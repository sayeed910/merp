<?php


namespace App\Helper;



use Assert\Assertion;

class Assert
{
    public static function notNull($actual, $message = ''){
        Assertion::notNull($actual, $message);

    }

}
