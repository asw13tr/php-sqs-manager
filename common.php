<?php

use QfsTest\Input;

require 'vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    Input::init();
?>
