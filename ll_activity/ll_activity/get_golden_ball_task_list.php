<?php
/**
 * 摘金球任务列表
 */

require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityTaskManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/LLActivityGoldenBallManager.php";

FlamingoLogger::getInstance()->Logln($_REQUEST);
$response = array(
	'code'=> 0,
	'err_msg' => '',
	'data'=> '',
);


$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;
$login_key = isset($_REQUEST['login_Key'])? $_REQUEST['login_Key']:"";
$uuid = isset($_REQUEST['uuid'])? $_REQUEST['uuid']:"";
$productID = isset($_REQUEST['productID'])? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType'])? $_REQUEST['platformType']:102;
$appid = isset($_REQUEST['appID'])?$_REQUEST['appID']:0;
$tid = isset($_REQUEST['task_id'])? $_REQUEST['task_id'] : 8;
/*
//判断登录时间
$nowTime = date('Y-m-d H:i:s');
if(strtotime($nowTime)<strtotime(Activity_Start_Time)){
	$response['code']=ErrorCode::Activity_Not_Start;
}
if(strtotime($nowTime)>strtotime(Activity_End_Time)){
	$response['code']=ErrorCode::Activity_Had_End;
}
if($response['code']!==0){
	$response['err_msg']=ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}
 */
/*$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key,$appid)) {
	$response['code'] =     ErrorCode::User_Not_Login;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}
 */

$manager = new LLActivityGoldenBallManager();
$task = $manager->getUserTask($uin);
$result = array();
if (!$task) {
	$response['data'] = [];
	echo json_encode($response);
	exit();
}
foreach ($task as $k=>$v) {
	$t = array();
	$t['task_name'] = $v['task_name'];
	$t['task_id'] = $v['task_id'];
	$t['status'] = $v['status'];
	$result[] = $t;
}

$response['data'] = $result;
echo json_encode($response);
exit();
