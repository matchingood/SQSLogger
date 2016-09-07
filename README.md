# SQSLogger

Loggin library for Laravel application with AWS SQS

## Install
In your composer.json,
```
"matchingood/sqs-logger": "^0.1"
```

Then you register SQSLogger at `config/app.php`.
```php
'providers' => [
    .
    .
    .
    Matchingood\SQSLogger\SQSLoggerServiceProvider::class
],
.
.
.
'aliases' => [
    .
    .
    .
    'SQSLogger' => Matchingood\SQSLogger\Facades\SQSLogger::class
],
```

You can create the configuration file to execute
```
$ php artisan vendor:publish
```

Then you can configure `app/sqslogger.php`
```
return [
    'env' => "if not 'prod', this library use Laravel Log class",

    'aws' => [
        'access_key' => "AWS access key",
        'access_secret' => "AWS access secret",
        'sqs' => [
            'version' => "API version",
            'region' => "AWS region",
            'queue_name' => "SQS queue name"
        ]
    ]
];
```

## Usage
```php
<?php

use SQSLogger;

class Test
{
    public function test()
    {
        SQSLogger::info("info");
        SQSLogger::error("error");
        SQSLogger::access("access");
        SQSLogger::critical("access");
    }
}
```

You can add more information like this.
```php
SQSLogger::info('info', ['hello' => 'world']);
```
