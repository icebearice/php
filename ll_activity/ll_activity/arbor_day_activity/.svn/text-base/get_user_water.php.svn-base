<?php
/*************************************************************************
* File Name: get_user_water.php
* Author: lvchao.yan
* Created Time: 2019.02.22
* Desc: 获取用户水源信息
*************************************************************************/

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

addActivityUser($uin);  //将初次参加活动的用户入库 

$water = Watering::getInstance()->showWater($uin);  //获取用户水源信息

if($water == -2){
    $response['code'] = ErrorCode::Today_Water_Is_Exhausted;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

if($water == -3){
    $response['code'] = ErrorCode::Have_No_Water;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$response['data'][] = $water; 
echo json_encode($response);
exit();
