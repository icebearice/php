<?php
/*************************************************************************
 * File Name: watering.php
 * Author: lvchao.yan
 * Created Time: 2019.02.25
 * Desc: 浇水
 ************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/arbor_day_activity/Watering.class.php";
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
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : "";
if (!$uuid) {                                                                                                                                        
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$uuid = md5("ll_web_login_{$_SERVER['HTTP_USER_AGENT']}");
	}   
}   
$product_id = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 151;

addActivityUser($uin);  //将初次参加活动的用户入库 

$prize_info = Watering::getInstance()->watering($uin);  //test

if ($prize_info == -1) {
	$response['code'] = ErrorCode::DataBase_Not_OK;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

if($prize_info == -2){
	$response['code'] = ErrorCode::Today_Water_Is_Exhausted;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

if($prize_info == -3){
	$response['code'] = ErrorCode::Have_No_Water;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

giveUserPrize($uin, $prize_info['prize_id'], $uuid, $product_id);

$response['data'] = $prize_info;
echo json_encode($response);
exit();
