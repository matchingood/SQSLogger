# SQSLogger

## Function

* ERROR/INFOレベルのログメッセージ、及びアクセス記録を1つのメソッドでSQSへ送信します。
* 実装が簡素なので拡張が容易です。
* 本番環境以外の環境では、Laravelのログ機能を使いローカルで記録するので、開発中に発生したエラーの特定も容易です。

## Preparation

1. 使用するプロジェクトのcomposer.jsonに以下を記述します。

```json
    "require-dev": {
        .
        .
        .
        "matchingood/sqs-logger": "~1.0"
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

2. 端末上で以下を実行します。

```sh
$ composer config -g github-oauth.github.com 7b748f8f286a2b9fa2e723c484d25ad15f9970ae
```

3. composer updateを実行します。

4. .envに以下を記述します
```
APP_ENV=## 本番環境ならprodに指定 ##
AWS_ACCESS_KEY_ID=## AWSのアクセスキー ##
AWS_SECRET_ACCESS_KEY=## AWSのシークレットアクセスキー ##
AWS_SQS_NAME=## 使用するSQS上のQueueの名前 ##
```

## Usage
```php
<?php

use Matchingood\SQSLogger

class Test
{
    private $logger;

    public function __construct()
    {
        $this->logger = new SQSLogger;
    }

    public function test()
    {
        $this->logger->info("info"); // 引数をINFOレベルのログとしてSQSへ送信
        $this->logger->error("error"); // 引数をERRORレベルのログとして送信
        $this->logger->access("access"); // 引数をACCESSレベルのログとして送信
    }
}
```

