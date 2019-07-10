<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityPrizeManager.php";
$uin = 1201;

$limit_credit = 66;
$vid = 2734;
$loginKey = 'test-flamingo-login-key-abc';
$uuid = '';
$productID=136;
$platform = 102;
$uin = 1203;
$handler = new LLActivityPrizeManager();
$result = $handler->addPrizeDivide($uin,$limit_credit,$loginKey,$uuid,$productID,$platform);
var_dump($result);
