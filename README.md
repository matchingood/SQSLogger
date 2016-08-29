# SQSLogger

## Function

* ERROR/INFOレベルのログメッセージ、及びアクセス記録を1つのメソッドでSQSへ送信します。
* 実装が簡素なので拡張が容易です。
* 本番環境以外の環境では、Laravelのログ機能を使いローカルで記録するので、開発中に発生したエラーの特定も容易です。

## Preparation

1\. 使用するプロジェクトのcomposer.jsonに以下を記述します。
```json
    "require-dev": {
        .
        .
        .
        "matchingood/sqs-logger": "dev-master"
    },
    .
    .
    .
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/matchingood/SQSLogger"
        }
    ]
```
2\. 端末上で以下を実行します。

```sh
$ composer config -g github-oauth.github.com [GitHub Token]
```

3\. composer updateを実行します。

4\. config/app.phpに以下を追記します。

```
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

5\. php artisan vendor:publishを実行します

* これによって、config/以下にsqslogger.phpが生成されます。
* それぞれの項目は以下に対応しています。
```
return [

    'env' => ## prodであればSQSに送信。そうでなければローカルで出力 ##,

    'aws' => [
        'access_key' => ## AWSのアクセスキー ##,
        'access_secret' => ## AWSのシークレットアクセスキー ##,
        'sqs' => [
            'version' => ## 使用するAPIのバージョン ##,
            'region' => ## SQSのリージョン ##,
            'queue_name' => ## 使用するSQS上のQueueの名前 ##
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
        SQSLogger::info("info"); // 引数をINFOレベルのログとしてSQSへ送信
        SQSLogger::error("error"); // 引数をERRORレベルのログとして送信
        SQSLogger::access("access"); // 引数をACCESSレベルのログとして送信
    }
}
```
