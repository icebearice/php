<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityTaskManager.php";
$uin = 418;
$handler = new LLActivityTaskManager();
$result = $handler->getTaskInfo($uin);
var_dump($result);
