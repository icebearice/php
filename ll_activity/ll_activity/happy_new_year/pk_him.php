<?php
/***********************************************************************
 * File Name:pk_him.php 
 * Author: lvchao.yan
 * Created Time: 2018.12.26
 * Desc: 干他！！
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

$his_id = isset($_REQUEST['his_id'])?$_REQUEST['his_id']:124;  //被PK者的ID

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

$res = ContriValuePK::getInstance()->pkHim($uin,$his_id);
if(!is_array($res)){  //出错
	if ($res == -1) {  //数据库炸了
		$response['code'] = ErrorCode::DataBase_Not_OK;
		$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	} else if ($res == -2) {  //剩余PK次数不足
		$response['code'] = ErrorCode::PK_Times_Is_Not_Enough;
		$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	} else {  //被PK者ID不存在
		$response['code'] = ErrorCode::His_ID_Is_Not_Exists;    
		$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	}
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

if ($res['prize']) {  //发放奖励
	giveUserPrize($uin,$res['prize']['prize_id'],$uuid,$product_id);
}

$response['data'] = $res;
//print_r($response);exit();
echo json_encode($response);
exit();
