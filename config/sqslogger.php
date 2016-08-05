<?php

/*
 * configuration file for SQSLogger
 * see: https://github.com/matchingood/SQSLogger
 *
 */

return [

    'env' => env('APP_ENV', 'local'),

    'aws' => [
        'access_key' => env('AWS_ACCESS_KEY_ID', 'forge'),
        'access_secret' => env('AWS_SECRET_ACCESS_KEY', 'forge'),
        'sqs' => [
            'version' => env('SQS_VERSION', 'latest'),
            'region' => env('SQS_REGION', 'us-east-1'),
            'queue_name' => env('AWS_SQS_NAME', 'forge'),
        ]
    ]
];
