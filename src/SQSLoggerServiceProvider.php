<?php

namespace Matchingood\SQSLogger;

use Illuminate\Support\ServiceProvider;

class SQSLoggerServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton('sqslogger', function() {
            return new SQSLogger;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '../config/sqslogger.php' => config_path('sqslogger.php')
        ]);
    }
}
