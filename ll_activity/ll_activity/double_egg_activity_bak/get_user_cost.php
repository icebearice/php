<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/LLActivityTaskManager.php";
require_once dirname(__FILE__) . "/commonFunctions.php";

FlamingoLogger::getInstance()->Logln($_REQUEST);
$response = array(
	'code'=> 0,
	'err_msg' => '',
	'data'=> '',
);


$login_key = isset($_REQUEST['login_key'])? $_REQUEST['login_key']:"";

list($code,$msg) = checkActivityTime();
if($code !== 0){
	$response['code'] = $code;
	$response['err_msg'] = $smg;
	echo json_encode($response);
	exit;
} 

list($code,$msg,$data) = checkUserLogin($login_key);
if ($code !== 0) {
	$response['code'] = $code;
	$response['err_msg'] = $msg;
	echo json_encode($response);
	exit;
}


$handler = new LLActivityTaskManager();
$result = $handler->getUserTodayCost($data['uin']);
if($result['code']===0){
	$response['data']=$result['result'];
}
$response['code']=$result['code'];
$response['err_msg']=ErrorCode::getTaskError($response['code']);
echo json_encode($response);
FlamingoLogger::getInstance()->Logln($response);

exit();
