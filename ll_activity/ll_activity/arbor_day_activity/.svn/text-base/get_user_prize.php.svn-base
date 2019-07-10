<?php
/*************************************************************************
 * File Name: get_user_prize.php
 * Author: lvchao.yan
 * Created Time: 2019.02.25
 * Desc: 获取用户奖励 
 *************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/arbor_day_activity/commonFunction.php";

$response = array(
	"code" => 0,
	"err_msg" => '',
	"data" => '',
);

checkActivityDate();

$res = isUserLogined();
$isUserLogined = $res[0];
if(!$isUserLogined){  //登陆态验证
	$response['code'] = ErrorCode::User_Not_Login;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

$uin = isset($res[1]['uin']) ? $res[1]['uin'] : 0;

addActivityUser($uin);  //将初次参加活动的用户入库 

$prize = UserInfo::getInstance()->getUserAllAcceptPrize($uin);
if (count($prize) > 0) { 
	for ($i = 0; $i < count($prize); $i ++) {
		$accept_time = $prize[$i]["accept_time"];
		$prize[$i]["accept_time"] = date("Y-m-d", strtotime($accept_time));
		$prize[$i]["prize_sour"] = "植树节活动专属奖励";
		if ($prize[$i]["prize_id"] == 29) {
			$prize[$i]["prize_sour"] = "植树节活动邀请好友奖励";
		}
	}
	$response['data'] = $prize; 
} 

echo json_encode($response); 
exit();
