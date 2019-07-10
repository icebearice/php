
<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
$uin = 390;
$handler = new LLUserInfoServer();
$result = $handler->getUserInfoByUin($uin);
var_dump($result);

