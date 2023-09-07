<?php

namespace QfsTest;
use Aws\Exception\AwsException;

class SQSQueueSender extends SQSQueueClient {


    protected $params = [];
    private $messageBody = null;
    private $attributes = [];
    private $delaySeconds = 0;

    private $result = null;

    public const TYPE_STRING = "String";
    public const TYPE_NUMBER = "String";

    function __construct($clientDatas=[], $queueUrl=null){
        parent::__construct($clientDatas=[], $queueUrl=null);
    } // __construct

    public function setQueueUrl($queueUrl=null){
        $this->queueUrl = $queueUrl;
        return $this;
    }


    public function setDelay($seconds=0){
        $this->delaySeconds = $seconds;
        return $this;
    }
    public function setAttribute($name=null, $value=null, $type=self::TYPE_STRING){
        $this->attributes[$name] = [
            'DataType'      => $type,
            'StringValue'   => $value
        ];
        return $this;
    } // setAttribute

    public function setAttributes($attributes=[]){
        $this->attributes = $attributes;
        return $this;
    } // setAttributes

    public function setMessage($message=null){
        if(is_array($message) || is_object($message)){
            $message = json_encode($message);
        }
        $this->messageBody = $message;
        return $this;
    } // setMessage

    public function send(){
        $this->prepareParams();
        $this->sendMessage();
        return $this->result->status;
    }

    public function result(){
        return $this->result;
    }


    private function prepareParams(){
        $this->params = [
            'DelaySeconds'      => $this->delaySeconds,
            'MessageAttributes' => $this->attributes,
            'MessageBody'       => $this->messageBody,
            'QueueUrl'          => $this->queueUrl
        ];

    }



    private function sendMessage(){
        try {
            $response = $this->client->sendMessage($this->params);
            $this->result = (object) [
                "status"        => true,
                "id"            => $response->get("MessageId"),
                "body"          => $response->get("MD5OfMessageBody"),          // md5
                "attributes"    => $response->get("MD5OfMessageAttributes"),    // md5
                "order"         => $response->get("SequenceNumber"),
            ];

        } catch (AwsException $e) {
            error_log($e->getMessage());
            $this->result = (object) [
                "status"    => false,
                "message"   => $e->getMessage(),
                "error"     => $e
            ];
        }
    }



}
