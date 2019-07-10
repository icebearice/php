<?php
require_once("UserInfo.class.php");
require_once SYSDIR_UTILS . "/voucherServer.class.php";
require_once SYSDIR_UTILS . "/grouthServer.class.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/arbor_day_activity/commonFunction.php";

function dayReset(){    //日清
	sleep(2);
	$__db = new Db();
	$__db->use_db("write");

	$sql = "select count(*) from arbor_day_prize_list_v2";
	$res = $__db->query($sql);
	$prize_count = $res[0]["count(*)"];

	for ($prize_id = 1; $prize_id < $prize_count; $prize_id ++) {
		$sql = "select prize_total from arbor_day_prize_list_v2 where prize_id = {$prize_id}";
		$res = $__db->query($sql);
		$prize_total = $res[0]["prize_total"];
		$sql = "update arbor_day_prize_list set prize_total = {$prize_total} where prize_id = {$prize_id}";
		$__db->query($sql);
		@file_put_contents('/tmp/arbor_day_sql.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);
	}

	$sql = "update arbor_day_user_list set water_100 = 0, water_500 = 0, water_1000 = 0";
	$__db->query($sql);
	@file_put_contents('/tmp/arbor_day_sql.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);
}

dayReset();
