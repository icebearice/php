<?php
/*************************************************************************
* File Name: get_daily_task_list.php
* Author: lvchao.yan
* Created Time: 2018.12.21
* Desc: 拉取每日任务列表
*************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/DailyTaskList.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

checkActivityDate();

$response = array(
    "code" => 0,
    "err_msg" => '',
    "data" => '',
);

$task = DailyTaskList::getInstance()->showDailyTaskList();  //所有任务信息
$all_id = array();  //所有任务id集
foreach($task as $t){  
    $all_id[]=$t['task_id'];
    $response['data'][] = array( 'task_id'=>$t['task_id'],'task_name'=>$t['task_name'],'task_times'=>$t['task_times'],'completed_times'=>0);
}

/* 用户未登录，所有任务完成次数皆为0 */
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

$res = $completed_task = DailyTaskList::getInstance()->getUserCompletedTask($uin);  //已完成任务集
if ($res == -1) {
	$response['code'] = ErrorCode::DataBase_Not_OK;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

if ($res) {  
    foreach($res as $task){  //修改已完成任务任务次数字段
		$task_id = $task['task_id'];
		for($i = 0; $i < count($response['data']); $i++){
			if ($response['data'][$i]['task_id'] == $task_id) {
				$response['data'][$i]['completed_times'] = $task['task_times'];
				break;
			}
		}
    }
}

//print_r($response);exit();
echo json_encode($response);
exit();
