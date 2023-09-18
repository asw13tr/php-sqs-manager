<?php
use QfsTest\Helper;
Helper::GetHeader();

$q = empty($_GET["q"])? 'sqs' : $_GET["q"];
$filename = $q . '.php';
$filepath = realpath(__DIR__ . '/pages/' . $filename);
if(!file_exists($filepath)){
    echo "No Page";
}else{
    require_once $filepath;
}


Helper::GetFooter();
?>