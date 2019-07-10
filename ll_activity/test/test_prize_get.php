<?php
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityPrizeManager.php";

$result = array();

$manager = new LLActivityPrizeManager();
for ($i = 0; $i<= 1000; $i++) {
	$data = $manager->getLotteryInfo();
	if (!isset($result[$data['money_id']])) {
		$result[$data['money_id']] = 0;
	}
	$result[$data['money_id']] ++;
}

foreach ($result as $k=>$v) {
	echo "money_id : {$k}   times:$v \r\n";
}

