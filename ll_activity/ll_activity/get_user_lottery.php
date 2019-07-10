<?php
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once dirname(dirname(__FILE__)) . "/utils/prize.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/question.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/error.class.php";
require_once dirname(dirname(__FILE__)) . "/utils/userAuthServer.class.php";

$response = array(
		'code' => 0,
		'er_msg' => '',
		'data' => '',
		);

$task_id = isset($_REQUEST['task_id'])?$_REQUEST['task_id']: 0;
$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;
$login_key = isset($_REQUEST['login_Key'])? $_REQUEST['login_Key']:"";
$uuid = isset($_REQUEST['uuid'])? $_REQUEST['uuid']:"";
$productID = isset($_REQUEST['productID'])? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platform'])? $_REQUEST['platform']:102;

$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key)) {
	$response['code'] =	ErrorCode::User_Not_Login; 
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}
$count = 99;
$isUndo = true;
$response['data']['lottery'] = LLPrizeManager::getInstance()->getPrizeHomePageInfo($uin, $task_id);
$response['data']['tasks'] = LLQuestionManager::getInstance()->getAllQuestion($login_key, $productID, $uuid, $platform,$uin, $task_id, $count, $isUndo);
$taskInfo =  LLQuestionManager::getInstance()->getQuestInfo($task_id); 
if (isset($taskInfo)) {
	$response['data']['task_name'] = $taskInfo['name'];
}

echo json_encode($response);
exit();
