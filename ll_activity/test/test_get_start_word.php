<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityMessageManager.php";
$top_num = 3;
$other_num = 26;
$total_num = 30;
$uin = 1201;
$loginFlag = TRUE;

$handler = new LLActivityMessageManager();
$result = $handler->getMessageInfo($uin,$top_num,$other_num,$loginFlag);
var_dump($result);
