<?php
/*************************************************************************
* File Name: get_daily_task_list.php
* Author: lvchao.yan
* Created Time: 2019.02.22
* Desc: 拉取每日任务列表
*************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/arbor_day_activity/DailyTask.class.php";
require_once SYSDIR_UTILS . "/arbor_day_activity/commonFunction.php";

$response = array(
    "code" => 0,
    "err_msg" => '',
    "data" => '',
);

checkActivityDate();

$daily_task_list = DailyTask::getInstance()->getDailyTaskList();  //每日任务列表
$response['data'][0] = $daily_task_list; 

$res = isUserLogined();
$isUserLogined = $res[0];
if(!$isUserLogined){  //登陆态验证
    $response['code'] = ErrorCode::User_Not_Login;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$uin = isset($res[1]['uin']) ? $res[1]['uin'] : 0;

addActivityUser($uin);  //将初次参加活动的用户入库 

$user_daily_task_list = DailyTask::getInstance()->getUserDailyTaskList($uin);
if ($user_daily_task_list == -1) {
	$response['code'] = ErrorCode::DataBase_Not_OK;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

$response['data'][0] = $user_daily_task_list;
echo json_encode($response);
exit();
