<?php
require_once dirname(dirname(__FILE__)) ."/include/config.inc.php";

require_once dirname(dirname(__FILE__)) . '/utils/LLIconManager.php';

$manager = new LLIconManager();
var_dump($manager->sendPlatformCoin(1203,1,"ceshi"));
