<?php
/*************************************************************************
 * File Name: get_my_prize.php
 * Author: lvchao.yan
 * Created Time: 2018.12.21
 * Desc: 获取用户奖励 
 *************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/AcceptYearPrize.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

//checkActivityDate(1);

$response = array(
	"code" => 0,
	"err_msg" => '',
	"data" => '',
);

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
	$response['data'] = $prize; 
} 

echo json_encode($response); 
exit();
