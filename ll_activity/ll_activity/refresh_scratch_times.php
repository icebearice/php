<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityScratchManager.php";
require_once SYSDIR_UTILS . "/logger.php";


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
$range = isset($_REQUEST['range'])?$_REQUEST['range']:1;

if($range!=1){
	$response['code']=ErrorCode::Activity_Not_Current;
	$response['err_msg']=ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit;
}
//验证用户登录状态态
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key,$appid)) {
	$response['code'] =     ErrorCode::User_Not_Login;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

$manager = new LLActivityScratchManager();
$info = $manager->getUserScratchTimes($uin,$range);
$response['data']['cratch_times'] = isset($info['scratch_times']) ? $info['scratch_times']:0;
$response['data']['prize_times'] = isset($info['prize_times']) ? $info['prize_times'] : 0;
echo json_encode($response);
exit();

