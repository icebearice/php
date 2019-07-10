<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityPrizeManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
FlamingoLogger::getInstance()->Logln($_REQUEST);
 
$response = array(
	'code'=>0,
	'err_msg'=>'',
	'data'=>'',
);
/*
//判断时间
if(strtotime(date('Y-m-d H:i:s'))<strtotime(Prize_Start_Time)){
	$response['code']=ErrorCode::Activity_Not_Start;
	$response['err_msg']=ErrorCode::getTaskError($response['code']);
	$response['data']['rewards']=array();
	echo json_encode($response);
	exit();
}
 */

$prizeHandler = new LLActivityPrizeManager();
$limit_num=30;
$res=$prizeHandler->getPrizeInfo($limit_num);
if($res['code']!==0){
	$response['code']=$result['code'];
	$response['err_msg']=ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}
$response['data']['rewards']=$res['result'];
echo json_encode($response);
FlamingoLogger::getInstance()->Logln($response);

exit();

