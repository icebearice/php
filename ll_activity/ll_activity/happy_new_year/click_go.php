<?php
/*************************************************************************
 * File Name: click_go.php
 * Author: lvchao.yan
 * Created Time: 2018.12.24
 * Desc: 用户点击GO抽取数字
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

$num = MarkSexRoller::getInstance()->click_go($uin);
if (!is_array($num)) {
	if ($num == -1) {
		$response['code'] = ErrorCode::DataBase_Not_OK;
		$response['err_msg'] = ErrorCode::getTaskError($response['code']);
		echo json_encode($response);
		exit();
	}
	if ($num == -2) {  //GO次数不足
		$response['code'] = ErrorCode::GO_Times_Is_Not_Enough;
		$response['err_msg'] = ErrorCode::getTaskError($response['code']);
		echo json_encode($response);
		exit();
	}
}
//echo $num."\n";exit();
$response["data"]['num'] = $num[0];
$response["data"]['go_times'] = $num[1];
echo json_encode($response);
exit();
