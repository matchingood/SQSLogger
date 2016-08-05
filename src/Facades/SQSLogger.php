<?php

namespace Matchingood\SQSLogger\Facades;

use Illuminate\Support\Facades\Facade;

class SQSLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sqslogger';
    }
}
