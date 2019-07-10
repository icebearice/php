<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityPrizeManager.php";

$prizeHandler = new LLActivityPrizeManager();
$limit_num=30;
$res=$prizeHandler->getPrizeInfo($limit_num);
var_dump($res);
