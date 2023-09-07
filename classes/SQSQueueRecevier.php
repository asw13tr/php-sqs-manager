<?php


namespace QfsTest;
class SQSQueueRecevier extends SQSQueueClient{

    public function __construct($clientDatas=[], $queueUrl=null){
        parent::__construct($clientDatas, $queueUrl);
    } // __construct

    public function setQueueUrl($queueUrl=null){
        $this->queueUrl = $queueUrl;
        return $this;
    }

    public function run()
    {
        while (true) {
            $receiver = $this->client->receiveMessage($this->getRecevierOptions());

            if ($receiver->get('Messages')) {
                $messageObject = $receiver->get('Messages')[0];

                $message = new \stdClass();
                $message->id = $messageObject['MessageId'];
                $message->body = json_decode($messageObject['Body']);
                $message->target = $messageObject['ReceiptHandle'];

                $this->handler($message);
            } else {
                echo "Kein Nachricht.";
                sleep(10);
            }

        }
    } // run


    protected function handler($message)
    {
        $resultMsg = "";
        $resultMsg .= "=================================================" . "\n";
        $resultMsg .= "Current Message: " . $message->body->text . "\n";
        $resultMsg .= "Job: " . $message->body->job . "\n";
        $resultMsg .= "Company: " . $message->body->company . "\n";
        $resultMsg .= "Table: " . $message->body->table . "\n";
        $resultMsg .= "Benutzer: " . $message->body->benutzer . "\n";
        print_r($resultMsg);

        // Löschen Nachricht aus der Warteschlange, wenn dieser Job erfolgreich ist.
        if (true) {
            $this->delete($message);
        }
        sleep(5);
    } // handler


    protected function getRecevierOptions()
    {
        return [
            "QueueUrl" => $this->queueUrl,
            'AttributeNames' => ['SentTimestamp'],   // Wann wurde die Nachricht gesendet?
            "MaxNumberOfMessages" => 1,
            "VisibilityTimeout" => 10, // Die Nachrichten werden nach 1 Sekunde wieder sichtbar
            "WaitTimeSeconds" => 20 // Während dieser Zeit wartet SQS auf eine neue Nachricht.
        ];
    }

    protected function delete($message)
    {
        $result = $this->client->deleteMessage([
            'QueueUrl' => $this->queueUrl,
            'ReceiptHandle' => $message->target
        ]);
    }


}

?>