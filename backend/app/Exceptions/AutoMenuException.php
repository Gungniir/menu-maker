<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AutoMenuException extends ReportableException
{
    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
