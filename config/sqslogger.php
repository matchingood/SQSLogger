<?php

return [
    'env' => env('APP_ENV', 'local'),

    'aws' => [
        'access_key' => env('AWS_ACCESS_KEY_ID', 'forge'),
        'access_secret' => env('AWS_SECRET_ACCESS_KEY', 'forge'),
        'sqs_name' => env('AWS_SQS_NAME', 'forge')
    ]

];
