<?php
/*************************************************************************
* File Name: get_user_data.php
* Author: lvchao.yan
* Created Time: 2018.12.25
* Desc: 获取用户数据
*************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/arbor_day_activity/UserInfo.class.php";
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

$user_data_arr = UserInfo::getInstance()->getUserData($uin);
if(!$user_data_arr){
	echo json_encode($response);
	exit();
}

$uid = isset($user_data_arr['base_data']['uid']) ? $user_data_arr['base_data']['uid'] : 0;
$uname = isset($user_data_arr['base_data']['uname']) ? $user_data_arr['base_data']['uname'] : '';
$unickname = isset($user_data_arr['base_data']['unickname']) ? $user_data_arr['base_data']['unickname'] : "可爱的66玩家";
$usex = isset($user_data_arr['ext_data']['usex']) ? $user_data_arr['ext_data']['usex'] : 0;  //0=>男；1=>女
$uphone = isset($user_data_arr['base_data']['uphone']) ? $user_data_arr['base_data']['uphone'] : 0;
$uicon = isset($user_data_arr['ext_data']['uico']) ? $user_data_arr['ext_data']['uico'] : '';
$uvip = UserInfo::getInstance()->getVipLevel($uin);
$response['data'] =array('uid'=>$uid,'unickname'=>$unickname,'usex'=>$usex,'uicon'=>$uicon,'uvip'=>$uvip,'uphone'=>$uphone,'uname'=>$uname);

echo json_encode($response);
exit();
