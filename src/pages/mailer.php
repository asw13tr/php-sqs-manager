<?php

require_once realpath(__DIR__ . "/../../classes/CPMailer.php");
require_once realpath(__DIR__ . "/../../classes/CPMailer.php");

$datas = [
        "host" => "smtp.mailhost.com",
        "user" => "username@mailhost.com",
        "pass" => "emailpassword"
];

$title = "Deneme e-postası";
$body  = "<p>Bu bir <strong>Denemedir</strong> <em>falan filan</em> olarak bir mail.</p>";


$mailer = new \CPMailer($datas);
$sentMail = $mailer->from('sender@mailhost.com')
                    ->to("recevier@mailhost.com")
                    ->setRetryLimit(1)
                    ->content($title, $body)
                    ->send();


echo "<pre>";
print_r($sentMail);

?>