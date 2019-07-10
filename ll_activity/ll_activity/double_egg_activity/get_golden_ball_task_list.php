<?php

/**
 * 摘金球任务列表
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/LLActivityGoldenBallManager.php";
require_once dirname(__FILE__) . "/commonFunctions.php";
require_once SYSDIR_UTILS."/LLActivityGoldenBollPrize.php";

FlamingoLogger::getInstance()->Logln($_REQUEST);
$response = array(
	'code'=> 0,
	'err_msg' => '',
	'data'=> [],
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


$manager = new LLActivityGoldenBallManager();
$task = $manager->getUserTask($data['uin'],$data['login_key'],$data['uuid'],$data['product_id'],$data['platform'],$data['appid']);
$result = array();
if (!$task) {
	echo json_encode($response);
	exit();
}

foreach ($task as $k=>$v) {
	$t = array();
	$t['task_name'] = $v['task_name'];
	$t['task_id'] = $v['task_id'];
	$t['status'] = $v['status'];
	$t['uin'] = $data['uin'];
	$t['prize_type'] = $v['prize_type'];
	$t['money'] = $v['money'];
	if(isset($v['share_num'])) {
		$t['share_num'] = $v['share_num'];
	}
	$result[] = $t;
}

$response['data'] = $result;
echo json_encode($response);
exit();
