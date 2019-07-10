<?php
/**
 * 双旦活动我的奖励列表
 *
 */
//ini_set("display_errors", "On");
require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/LLActivityMyReward.php";
require_once dirname(__FILE__) . "/commonFunctions.php";

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
	$response['err_msg'] = $smg;
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

$manager = new LLActivityMyReward();
$reward = $manager->getMyReward($data['uin']);
file_put_contents('/tmp/activity01.log',var_export($reward,true),FILE_APPEND);
//print_r($reward);

$response['data'] = $reward;
echo json_encode($response);
exit();
