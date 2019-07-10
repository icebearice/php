<?php
$_SERVER['RUN_MODE'] = 'development';

require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/voucherServer.class.php";

$voucherServer = new LLVoucherServer();
$vid = 2734;
$loginKey = 'test-flamingo-login-key-abc';
$uuid = '';
$productID=136;
$platform = 102;
$uin = 1203;
//$res = $voucherServer->sendVoucher($uin,$loginKey,$uuid,$productID,$platform,$vid);

var_dump($res);

