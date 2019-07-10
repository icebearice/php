<?php
/*************************************************************************
 * File Name: swop_prize.php
 * Author: lvchao.yan
 * Created Time: 2018.12.24
 * Desc: 用户合成2019奖励
 ************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/MarkSexRoller.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

checkActivityDate();

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

$prize_info = MarkSexRoller::getInstance()->swop_prize($uin);  //test

if ($prize_info == -1) {
	$response['code'] = ErrorCode::DataBase_Not_OK;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

if($prize_info == -2){  //没有集齐2019
	$response['code'] = ErrorCode::Not_Enough_2019;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : "";  //APP
if (!$uuid) {                                                                                                                                        
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$uuid = md5("ll_web_login_{$_SERVER['HTTP_USER_AGENT']}");
	}   
}   
$product_id = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 151;
giveUserPrize($uin,$prize_info['prize_id'],$uuid,$product_id);  //发放奖励
$response['data'] = $prize_info;
//print_r($response);exit();
echo json_encode($response);
exit();
