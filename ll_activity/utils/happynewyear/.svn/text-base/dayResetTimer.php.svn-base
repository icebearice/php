<?php
require_once("UserInfo.class.php");
require_once SYSDIR_UTILS . "/voucherServer.class.php";
require_once SYSDIR_UTILS . "/grouthServer.class.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

function dayReset(){    //日清
	$__db = new Db();
	$__db->use_db("write");

	// PK次数日清
	$sql = "update ll_activity_user_list_of_activity set pk_times = 2";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	// GO次数日清
	$sql = "update ll_activity_user_list_of_activity set go_times = 0";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	// 年货日清
	$sql = "update ll_activity_prize_list set prize_total = 99999999 where prize_id = 1";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 800 where prize_id = 2";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 500 where prize_id = 3";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 200 where prize_id = 4";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 250 where prize_id = 5";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 100 where prize_id = 6";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	// 合成奖励日清
	$sql = "update ll_activity_prize_list set prize_total = 1 where prize_id = 24";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 20 where prize_id = 25";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 40 where prize_id = 26";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 200 where prize_id = 27";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 99999999 where prize_id = 28";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);

	$sql = "update ll_activity_prize_list set prize_total = 99999999 where prize_id = 29";
	$__db->query($sql);
	@file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    ".$sql."\n\n",FILE_APPEND);
}

dayReset();
