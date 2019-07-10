<?php
require_once("UserInfo.class.php");
require_once SYSDIR_UTILS . "/voucherServer.class.php";
require_once SYSDIR_UTILS . "/grouthServer.class.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

function givePrize($days = 0){    //发放奖励：代金券、成长值
	$last_day_timestamp = strtotime(date("Y-m-d")) - $days;
	$last_day = date("Y-m-d",$last_day_timestamp);
	$res = getUserCostRanking(20,0,0,$last_day);
	for($i = 1; $i < count($res); $i ++){
		if ($res[$i]['ranking'] == 1) {
			@giveUserPrize($res[$i]['uin'], 17, '', 151, $login_key='', $platform=102,$appid = 0);
			registerAward($res[$i]['uin'], 17);
	        @file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    奖品ID：17"."\n\n",FILE_APPEND);
		} else if ($res[$i]['ranking'] >= 2 && $res[$i]['ranking'] <= 4 ) {
			@giveUserPrize($res[$i]['uin'], 18, '', 151, $login_key='', $platform=102,$appid = 0);
			registerAward($res[$i]['uin'], 18);
	        @file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    奖品ID：18"."\n\n",FILE_APPEND);
		} else if ($res[$i]['ranking'] >= 5 && $res[$i]['ranking'] <= 10) {
			@giveUserPrize($res[$i]['uin'], 19, '', 151, $login_key='', $platform=102,$appid = 0);
			registerAward($res[$i]['uin'], 19);
	        @file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    奖品ID：19"."\n\n",FILE_APPEND);
		} else {
			@giveUserPrize($res[$i]['uin'], 20, '', 151, $login_key='', $platform=102,$appid = 0);
			registerAward($res[$i]['uin'], 20);
	        @file_put_contents('/tmp/crontab_task.log',date('Y-m-d H:i:s')."    奖品ID：20"."\n\n",FILE_APPEND);
		}
	}
}

givePrize(86400);
