<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityScholarManager.php";
require_once SYSDIR_UTILS . "/LLActivityPrizeManager.php";
require_once SYSDIR_UTILS . "/LLActivityCreditManager.php";

$uin = 858;
$start = 0;
$count = 15;
$prizeHandler = new LLActivityPrizeManager();
$scholarHandler = new LLActivityScholarManager();
$creditHandler = new LLActivityCreditManager();
$res = $prizeHandler->getPrizeRecords($uin,$start,$count); 
$result = $scholarHandler->getScholarshipRecord($uin,$start,$count); 
$resultCreditLog = $creditHandler->getCreditInfo($uin,$start,$count);
var_dump($resultCreditLog);
var_dump($res);
var_dump($result);
