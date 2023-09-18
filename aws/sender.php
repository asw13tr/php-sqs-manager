<?php
require __DIR__."/../common.php";
use QfsTest\Input;
use QfsTest\SQSQueueSender;

$messageDatas = [
    "text"      => Input::Post("message", null),
    "job"       => "export_excel",
    "table"     => "pruefungen",
    "company"   => "cp2",
    "benutzer"  => "1000-1002",
    "query"     => "1000-1002/cp2/0018/pr_combo_kw_jahr:leer*pr_combo_qualifik:leer*pr_combo_intervall:leer*pr_combo_kataloge:leer*pr_combo_kw_jahrVon:leer*pr_combo_kw_jahrBis:leer*ch_xls_offen:NOCHECK*ch_xls_ok:NOCHECK*ch_xls_mangel:NOCHECK*ch_xls_gefahr:NOCHECK*ch_xls_nv:NOCHECK*ch_xls_nb:NOCHECK*ch_xls_abgelaufen:NOCHECK*ch_xls_ma_erl:NOCHECK*ch_xls_ge_erl:NOCHECK*ch_xls_ma_bea:NOCHECK*ch_xls_ge_bea:NOCHECK*ch_xls_ma_gem:NOCHECK*ch_xls_ge_gem:NOCHECK*ch_xls_ma_abg:NOCHECK*ch_xls_ge_abg:NOCHECK*ch_xls_grit_filter:NOCHECK*grid_art:ALLE*/pruefungen/VS"
];


$sender  = new SQSQueueSender();
$message = $sender->setMessage($messageDatas)
    ->setAttribute("Title", "Export für Pruefungen", SQSQueueSender::TYPE_STRING)
    ->setAttribute("Author", "QFS", SQSQueueSender::TYPE_STRING)
    ->setAttribute("WeeksOn", "1", SQSQueueSender::TYPE_NUMBER)
    ->send();

if($message){
    // sendet
}else{
    // error
}

echo json_encode($sender->result());