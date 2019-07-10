<?php
$_SERVER['RUN_MODE'] = 'developer';
require_once dirname(dirname(__FILE__)) . "/utils/GPUserWeChatHandler.class.php";

$manager = new GPUserWechatHandler();
//var_dump($manager->getUserWechatInfo(2676907));
var_dump($manager->getUserWechatInfo(296745602676907));
