<?php

/**
 * 获取用户活动信息 
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityTaskManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
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


list($code,$msg,$data) = checkUserLogin($llusersessionid,$uin,$login_key,$uuid,$product_id,$platform,$appid);
if($code !== 0) {
	$response['code'] = $code;
	$response['err_msg'] = $msg;
	echo json_encode($response);
	exit;
}

list($code,$msg) = checkActivityTime();
if($code !== 0){
	$response['code'] = $code;
	$response['err_msg'] = $msg;
} 


$manager = new LLActivityTaskManager();
$tinfo = $manager->getUserTaskInfo($data['uin'],9);
if (isset($tinfo)) {
	$response['data']['is_can_break'] = 0;
}else {
	$response['data']['is_can_break'] = 1;
}

$response['data']['is_can_break'] = 1;//测试

$costInfo = $manager->getUserTodayCost($data['uin']);

$response['data']['uin'] = $data['uin'];
$response['data']['user_name'] = '';
$response['data']['user_cost'] = 0;
$response['data']['user_rank'] = 0;
$response['data']['previous_cha'] = 0;
$response['data']['is_band_phone'] = 0;
$response['data']['user_name'] = '可爱的66玩家';
$response['data']['uico'] = '';
$response['data']['last_rank'] = 0;
$response['data']['prize_name'] = '';
if($costInfo) {
	$response['data'] = array_merge($response['data'],$costInfo);
}

//统计昨天的排名并发放奖励
$t = date('Y-m-d');

if ($t == Double_Activity_End_Time) {
   list($last_rank,$prize_name) = $manager->getLastDayCost($data['uin'],$data['login_key'],$data['uuid'],$data['product_id'],$data['appid']);
   if ($last_rank > 0) {
	   $response['data']['last_rank'] = $last_rank;
	   $response['data']['prize_name'] = $prize_name;
   }
}

$obj = new LLUserInfoServer();
$user_info = $obj->getUserInfoByUin($data['uin']);
if($user_info) {
	$user_info_arr = json_decode(json_encode($user_info),true);
	if (isset($user_info_arr['ext_data']['uico'])) {
	   $response['data']['uico'] = $user_info_arr['ext_data']['uico'];
	}
	//print_r($user_info_arr);die;
    $response['data']['user_name'] = '一个可爱的66用户';
	if(isset($user_info_arr['base_data']['unickname'])) {
       $response['data']['user_name'] = $user_info_arr['base_data']['unickname'];

	}
	
	if(isset($user_info_arr['base_data']['uphone'])) {
		$response['data']['is_band_phone'] = 1;
	}
}

echo json_encode($response);
exit();
