<?php

/**
 * 用户砸金蛋请求
 *
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/LLActivityAggPrize.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once dirname(__FILE__) . "/commonFunctions.php";
require_once SYSDIR_UTILS . "/LLActivityUserKnockEgg.php";
require_once SYSDIR_UTILS . "/LLActivityTaskManager.php";

FlamingoLogger::getInstance()->Logln($_REQUEST);
$response = array(
	'code'=> 0,
	'err_msg' => '',
	'data'=> '',
);

$llusersessionid = isset($_REQUEST['llusersessionid'])? $_REQUEST['llusersessionid']:"";//网页登录

$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;                         
$login_key = isset($_REQUEST['login_Key'])? $_REQUEST['login_Key']:"";      
$uuid = isset($_REQUEST['uuid'])? $_REQUEST['uuid']:"";                     
$product_id = isset($_REQUEST['productID'])? $_REQUEST['productID'] : 151;   
$platform = isset($_REQUEST['platformType'])? $_REQUEST['platformType']:102;
$appid = isset($_REQUEST['appID'])?$_REQUEST['appID']:0;                    

list($code,$msg) = checkActivityTime();
if($code !== 0){
	$response['code'] = $code;
	$response['err_msg'] = $msg;
	echo json_encode($response);
	exit;
} 

list($code,$msg,$data) = checkUserLogin($llusersessionid,$uin,$login_key,$uuid,$product_id,$platform,$appid);

if ($code !== 0) {
	$response['code'] = $code;
	$response['err_msg'] = $msg;
	echo json_encode($response);
	exit;
}
 

/*$data['uin'] = 1745;
$data['product_id'] = 151;
$data['uuid'] = 'aasss';
 */

$manager = new LLActivityAggPrizeManager();
$info = $manager->userGetEggs($data['uin'],$data['login_key'], $data['uuid'],$data['product_id'],102,0);
if (!isset($info)) {
	$response['code'] = 500;
	$response['err_msg'] = "砸蛋失败";
} 
else if ($info == 400) {
	$response['code'] = 400;
	$response['err_msg'] = "该ip今天已达到上限";
}
else if ($info == 404) {
	$response['code'] = 404;
	$response['err_msg'] = "您今天砸金蛋的额度用完了";
}else {
	$response['data'] = $info;
}
$joinObj = new LLActivityUserKnockEggManager();
$res = $joinObj->logJoinUser($data['uin']);

echo json_encode($response);
exit();
