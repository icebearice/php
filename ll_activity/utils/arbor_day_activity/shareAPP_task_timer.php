<?php
require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/development_config.inc.php";
require_once SYSDIR_UTILS . "/REDIS.php";
require_once SYSDIR_UTILS . "/arbor_day_activity/DailyTask.class.php";

$__cache = new myRedis();
$__cache->use_redis("read");
$__cache->redis->setOption(Redis::OPT_READ_TIMEOUT, -1);

while (1) {
	$uin_set = $__cache->redis->smembers("uin_set");
	if (!$uin_set) {
		sleep(1);
	} else {
		sleep(8);
		print_r($uin_set);
		$obj = new DailyTask();
		foreach ($uin_set as $uin) {
			$res = $obj->registerDailyTask($uin, SHARE_APP_TASK_ID);
			echo $uin.": ".$res."\n";
			$__cache->redis->srem("uin_set", $uin);
		}
		unset($obj);
	}
}
