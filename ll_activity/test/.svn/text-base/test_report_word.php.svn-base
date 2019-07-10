<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityMessageManager.php";
$uin = 11201;
$word = 'oh,no';
$addCredit = 6;
$handler = new LLActivityMessageManager();
$code = $handler->addMessage($word,$uin,$addCredit);
var_dump($code);
