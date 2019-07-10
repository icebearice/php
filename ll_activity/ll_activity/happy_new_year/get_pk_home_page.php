<?php
/*************************************************************************
* File Name:get_pk_home_page.php 
* Author: lvchao.yan
* Created Time: 2018.12.26
* Desc: 拉取PK主页(页面载入或刷新)
************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";

require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/ContriValuePK.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

checkActivityDate();

$response = array(
    "code" => 0,
    "err_msg" => '', 
    "data" => '', 
);

$three_user_data = ContriValuePK::getInstance()->getThreeUserData(0);
$response['data'] = array('contri_value'=>0,'pk_win_count'=>0,'three_user_data'=>$three_user_data);

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

$three_user_data = ContriValuePK::getInstance()->getThreeUserData($uin);  //登陆之后刷新数据
$response['data'] = array('contri_value'=>0,'pk_win_count'=>0,'three_user_data'=>$three_user_data);

$contri_value = UserInfo::getInstance()->getUserAllCost($uin);
$pk_win_count = ContriValuePK::getInstance()->getUserPKWinCount($uin);
$response['data']['contri_value'] = $contri_value;
$response['data']['pk_win_count'] = $pk_win_count;

echo json_encode($response);
exit();
