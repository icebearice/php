<?php

/**
 * 获取摘金球任务
 *
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityTaskManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/LLActivityGoldenBallManager.php";
require_once dirname(__FILE__) . "/commonFunctions.php";
require_once SYSDIR_UTILS . "/LLActivityUserKnockEgg.php";

//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);

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


$manager = new LLActivityGoldenBallManager();
$task = $manager->getGoldenBallTask($data['uin']);
if (!isset($task)) {
	$response['code'] = 404;
	$response['err_msg'] = '今天的额度已用完';
	echo json_encode($response);
	exit();
}
$joinObj = new LLActivityUserKnockEggManager();
$res = $joinObj->logJoinUser($data['uin']);
$response['data'] = $task;
echo json_encode($response);
exit();
