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
SQSLogger::info("info");
SQSLogger::error("error");
SQSLogger::critical("critical");

// Illuminate\Http\Request
SQSLogger::access($request);
```

You can add more information like this.
```php
SQSLogger::info('info', ['hello' => 'world']);
```

## SQS
SQSLogger sends json data to SQS in the production environment.
```json
{
    "level": "INFO",
    "time": "2016-09-07 17:30:00",
    "userId": 1,
    "message": "Hello World!"
}
```
The `userId` property will be -1 when `Auth::check()` returns false.

Only `ACCESS` level sends diferent json data, using `Illuminate\Http\Request` as a parameter.
```json
{
    "level": "INFO",
    "time": "2016-09-07 17:30:00",
    "userId": 1,
    "method": "POST",
    "accessUrl": "https://github.com/matchingood"
}
```
