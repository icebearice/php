<?php
/*************************************************************************
 * File Name: get_pk_top.php
 * Author: lvchao.yan
 * Created Time: 2018.12.21
 * Desc: 拉取PK排行榜 
 *************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/ContriValuePK.class.php";
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

$top_user_info = ContriValuePK::getInstance()->getPkTopUserInfo(10,$uin);
$response['data'] = $top_user_info;
//print_r($response);exit();
echo json_encode($response);
