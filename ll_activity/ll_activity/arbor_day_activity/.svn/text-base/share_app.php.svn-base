<?php
/*************************************************************************
* File Name: share_app.php
* Author: lvchao.yan
* Created Time: 2019.02.22
* Desc: 分享66手游APP
*************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/arbor_day_activity/DailyTask.class.php";
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

$res = DailyTask::getInstance()->shareApp($uin);
if ($res == 0) {
	$response['code'] = ErrorCode::Today_Already_Completed_The_Task;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

if ($res == -1) {
	$response['code'] = ErrorCode::DataBase_Not_OK;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

$response['data'][] = 1;
echo json_encode($response);
exit();
