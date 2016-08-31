<?php

namespace Matchingood\SQSLogger;

use Illuminate\Http\Request;
use Auth;
use Log;
use Aws\Sqs\SqsClient;

class SQSLogger
{
    private $sqs;
    private $url;

    public function __construct()
    {
        if (config('sqslogger.env') === 'prod') {
            $this->sqs = new SqsClient([
                'credentials' => [
                    'key' => config('sqslogger.aws.access_key'),
                    'secret' => config('sqslogger.aws.access_secret')
                ],
                'version' => config('sqslogger.aws.sqs.version'),
                'region' => config('sqslogger.aws.sqs.region')
            ]);
            $obj = $this->sqs->getQueueUrl([
                'QueueName' => config('sqslogger.aws.sqs.queue_name')
            ]);
            $this->url = $obj['QueueUrl'];
        } else {
            $this->sqs = null;
            $this->url = '';
        }
    }

    public function access(Request $request, $extraInfo = null)
    {
        $id = -1;
        if (Auth::check()) {
            $id = auth()->user()->id;
        }

        $body = [
            'level' => 'ACCESS',
            'time' => date('Y-m-d H:i:s'),
            'userId' => $id,
            'method' => $request->method(),
            'accessUrl' => $request->url(),
        ];

        if (!is_null($extraInfo)) {
            $body += $extraInfo;
        }

        $body = json_encode($body, JSON_UNESCAPED_SLASHES);

        $this->sendMessage([
            'MessageBody' => $body,
            'QueueUrl' => $this->url
        ]);
    }

    public function error($message, $extraInfo = null)
    {
        $this->sendMessage([
            'MessageBody' => $this->createBody('ERROR', $message, $extraInfo),
            'QueueUrl' => $this->url
        ], 'ERROR');
    }

    public function info($message, $extraInfo = null)
    {
        $this->sendMessage([
            'MessageBody' => $this->createBody('INFO', $message, $extraInfo),
            'QueueUrl' => $this->url
        ], 'INFO');
    }

    private function sendMessage($data, $level = 'debug', $extraInfo = null)
    {
        if (env('APP_ENV') === 'prod') {
            $this->sqs->sendMessage($data, $extraInfo);
        } elseif ($level === 'ERROR') {
            Log::error($data['MessageBody']);
        } elseif ($level === 'INFO') {
            Log::info($data['MessageBody']);
        } else {
            Log::debug($data['MessageBody']);
        }
    }

    private function createBody($level, $message, $extraInfo = null)
    {
        $id = -1;
        if (Auth::check()) {
            $id = auth()->user()->id;
        }

        $bt = debug_backtrace();

        $body = [
            'level' => $level,
            'time' => date('Y-m-d H:i:s'),
            'userId' => $id,
            'file' => $bt[1]['file'],
            'line' => $bt[1]['line'],
            'message' => $message
        ];

        if (!is_null($extraInfo)) {
            $body += $extraInfo;
        }

        return json_encode($body, JSON_UNESCAPED_SLASHES);
    }
}
