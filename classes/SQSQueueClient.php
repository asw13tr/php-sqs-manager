<?php

namespace QfsTest;
use Aws\Exception\AwsException;

class SQSQueueClient{

    protected $client = null;
    protected $queueUrl = null;

    function __construct($clientDatas=[], $queueUrl=null){
        $this->client = new \Aws\Sqs\SqsClient([
            "version" => ($clientDatas["version"] ?? @$_ENV["AWS_VERSION"]),
            "region" => ($clientDatas["region"] ?? @$_ENV["AWS_REGION"]),
            "credentials" => [
                "key" => ($clientDatas["accessKey"] ?? @$_ENV["AWS_ACCESS_KEY"]),
                "secret" => ($clientDatas["secretKey"] ?? @$_ENV["AWS_SECRET_KEY"]),
            ],
        ]);

        $this->queueUrl = ($queueUrl ?? $_ENV["AWS_QUEUE_URL"]);
    }

}