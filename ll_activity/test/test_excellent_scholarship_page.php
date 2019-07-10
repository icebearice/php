<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityScholarManager.php";
$uin = 858;
$handler = new LLActivityScholarManager(); 
$result = $handler->getScholarshipInfo($uin);

var_dump($result);
