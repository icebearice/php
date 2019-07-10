<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityPrizeManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
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

//判断登录时间
$nowTime = date('Y-m-d H:i:s');
if(strtotime($nowTime)<strtotime(Prize_Start_Time)){
	$response['code']=ErrorCode::Activity_Not_Start;
}
if(strtotime($nowTime)>strtotime(Prize_End_Time)){
	$response['code']=ErrorCode::Activity_Had_End;
}
if($response['code']!==0){
	$response['err_msg']=ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}
//验证登录态
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key,$appid)) {
	        $response['code'] =     ErrorCode::User_Not_Login;
			$response['err_msg'] = ErrorCode::getTaskError($response['code']);
			echo json_encode($response);
			 exit();
}
 
$limit_credit = 66;
$handler = new LLActivityPrizeManager();
$result = $handler->addPrizeDivide($uin,$limit_credit,$login_key,$uuid,$productID,$platform);
if($result['code']===0){
	$response['data']=$result['result'];
}
$response['code']=$result['code'];
$response['err_msg']=ErrorCode::getTaskError($response['code']);
echo json_encode($response);
FlamingoLogger::getInstance()->Logln($response);

exit();

