<?php
require __DIR__."/../common.php";
use QfsTest\Path;
use QfsTest\SQSQueueRecevier;

class JobFileWritter extends SQSQueueRecevier {

    protected function handler($message){
        $exportFolderName   = 'exports';
        $exportFilePath     = $exportFolderName . "/" .$message->id . '.txt';

        Path::createOrGetFolderPath("exports");
        $filePathFull = Path::createOrGetFilePath($exportFilePath);
        file_put_contents($filePathFull, json_encode( $message->body));
        sleep(5);
        $this->delete($message);
    }

}

$sqsRecevier = new JobFileWritter();
$sqsRecevier->run();



?>