<?php

namespace Matchingood;

use Illuminate\Http\Request;
use Auth;
use Log;
use Aws\Sqs\SqsClient;

class SQSLogger extends Illuminate\Support\ServiceProvider
{
    private $sqs;
    private $url;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '../config/sqslogger.php', 'sqslogger'
        ]);
    }

    public function __construct()
    {
        if (env('APP_ENV') === 'prod') {
            $this->sqs = new SqsClient([
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY')
                ],
                'version' => 'latest',
                'region' => 'us-east-1'
            ]);
            $obj = $this->sqs->getQueueUrl([
                'QueueName' => env('AWS_SQS_NAME', 'Log')
            ]);
            $this->url = $obj['QueueUrl'];
        } else {
            $this->sqs = null;
            $this->url = '';
        }
    }

    public function access(Request $request)
    {
        $id = -1;
        if (Auth::check()) {
            $id = auth()->user()->id;
        }

        $body = json_encode([
            'level' => 'ACCESS',
            'time' => date('Y-m-d H:i:s'),
            'userId' => $id,
            'method' => $request->method(),
            'accessUrl' => $request->url(),
        ], JSON_UNESCAPED_SLASHES);

        $this->sendMessage([
            'MessageBody' => $body,
            'QueueUrl' => $this->url
        ]);
    }

    public function error($message)
    {
        $this->sendMessage([
            'MessageBody' => $this->createBody('ERROR', $message),
            'QueueUrl' => $this->url
        ], 'ERROR');
    }

    public function info($message)
    {
        $this->sendMessage([
            'MessageBody' => $this->createBody('INFO', $message),
            'QueueUrl' => $this->url
        ], 'INFO');
    }

    private function sendMessage($data, $level = 'debug')
    {
        if (env('APP_ENV') === 'prod') {
            $this->sqs->sendMessage($data);
        } elseif ($level === 'ERROR') {
            Log::error($data['MessageBody']);
        } elseif ($level === 'INFO') {
            Log::info($data['MessageBody']);
        } else {
            Log::debug($data['MessageBody']);
        }
    }

    private function createBody($level, $message)
    {
        $id = -1;
        if (Auth::check()) {
            $id = auth()->user()->id;
        }

        $bt = debug_backtrace();

        return json_encode([
            'level' => $level,
            'time' => date('Y-m-d H:i:s'),
            'userId' => $id,
            'file' => $bt[1]['file'],
            'line' => $bt[1]['line'],
            'message' => $message
        ], JSON_UNESCAPED_SLASHES);
    }
}
