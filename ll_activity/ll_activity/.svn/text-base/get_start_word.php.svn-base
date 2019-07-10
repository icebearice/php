<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityMessageManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
FlamingoLogger::getInstance()->Logln($_REQUEST);
 
$response = array(
	'code'=>0,
	'err_msg'=>'',
	'data'=>'',
);
$loginFlag = TRUE;
//$start = isset($_REQUEST['start'])?$_REQUEST['uin']:0;
//$count = isset($_REQUEST['count'])?$_REQUEST['count']:10;


$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;
$login_key = isset($_REQUEST['login_Key'])? $_REQUEST['login_Key']:"";
$uuid = isset($_REQUEST['uuid'])? $_REQUEST['uuid']:"";
$productID = isset($_REQUEST['productID'])? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType'])? $_REQUEST['platformType']:102;
$appid = isset($_REQUEST['appID'])?$_REQUEST['appID']:0;

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
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key,$appid)) {
			$uin = 0;
			$response['code']=ErrorCode::User_Not_Login;
			$response['err_msg']=ErrorCode::getTaskError($response['code']);
			$loginFlag = FALSE;
}

//$uin = 1202;
$top_num = 3;
$other_num = 26;
$total_num = 30;

$handler = new LLActivityMessageManager();
$result = $handler->getMessageInfo($uin,$top_num,$other_num,$loginFlag);

if($result['code']!==0){
	$response['code']=$result['code'];
	$response['err_msg']=ErrorCode::getTaskError($response['code']);
	$response['data']['words']=array();
	$response['data']['word_done']='';
	echo json_encode($response);
	exit();
}


$response['data']=array('words'=>$result['result']);

$response['data']['word_done']=$result['word_done'];
echo json_encode($response);
FlamingoLogger::getInstance()->Logln($response);

exit();


