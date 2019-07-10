<?php
$_SERVER['RUN_MODE'] = 'development';
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once dirname(dirname(__FILE__)) . "/utils/prize.class.php";

$handler = LLPrizeManager::getInstance();
//var_dump($handler->getPrizeHomePageInfo(33));
var_dump($handler->userDrawPrize(103,1));
