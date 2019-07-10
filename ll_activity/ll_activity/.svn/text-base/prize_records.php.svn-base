<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityCreditManager.php";
require_once SYSDIR_UTILS . "/LLActivityScholarManager.php";
require_once SYSDIR_UTILS . "/LLActivityPrizeManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
FlamingoLogger::getInstance()->Logln($_REQUEST);
 

$response = array(
	'code'=>0,
	'err_msg'=>'',
	'data'=>'',
);

$type = isset($_REQUEST['type'])?$_REQUEST['type']:3;
$start = isset($_REQUEST['start'])?$_REQUEST['start']:0;
$count = isset($_REQUEST['count'])?$_REQUEST['count']:15;


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

//判断登录态
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key,$appid)) {
			$response['code'] =     ErrorCode::User_Not_Login;
			$response['err_msg'] = ErrorCode::getTaskError($response['code']);
			echo json_encode($response);
			 exit();
}
 

//$uin =3644 ;

$response['data']['credit_records']=array();
$response['data']['scholarship_records']=array();

$creditHandler = new LLActivityCreditManager();
$scholarHandler = new LLActivityScholarManager();
$prizeHandler = new LLActivityPrizeManager();

//获取红领巾总数
$result = $creditHandler->getCredit($uin);
if($result['code']!==0){
	$response['code']=$result['code'];
	$response['err_msg']=ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}
$response['data']['credit']=$result['result']['credit'];
$response['data']['today_credit']=$result['result']['today_credit'];

//红领巾积分记录
if($type === 1 || $type === 3){
	$result = $creditHandler->getCreditInfo($uin,$start,$count);
	if($result['code']!==0){
		$response['code']=$result['code'];
		$response['err_msg'] = ErrorCode::getTaskError($response['code']);
		echo json_encode($response);
		exit();
	}
	$response['data']['credit_records']=$result['result'];
} 
//开学礼包和学霸榜的记录
if($type ===2 || $type === 3){
	$res = $prizeHandler->getPrizeRecords($uin,$start,$count);
	if($res['code']!==0){
		$response['code']=$res['code'];
		$response['err_msg']=ErrorCode::getTaskError($response['code']);
		$response['data']['scholarship_records']=array();
		echo json_encode($response);
		exit();
	}
	$result = $scholarHandler->getScholarshipRecord($uin,$start,$count);
	if($result['code']!==0){
		$response['code']=$result['code'];
		$response['err_msg'] = ErrorCode::getTaskError($response['code']);
		$response['data']['scholarship_records']=array();
		echo json_encode($response);
		exit();
	}
	foreach($result['result'] as $v){
		$res['result'][]=$v;
	}
	$response['data']['scholarship_records']=$res['result'];
} 

echo json_encode($response);
FlamingoLogger::getInstance()->Logln($response);

exit();
















