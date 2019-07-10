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

$response = array(
    "code" => 0,
    "err_msg" => '',
    "data" => '',
);

$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;

$task = DailyTaskList::getInstance()->showDailyTaskList();  //所有任务信息
$all_id = array();  //所有任务id集
foreach($task as $t){  
    $all_id[]=$t['task_id'];
    $response['data'][] = array( 'task_id'=>$t['task_id'],'task_name'=>$t['task_name'],'task_times'=>$t['task_times'],'completed_times'=>0);
}

/* 用户未登录，所有任务完成次数皆为0 */
if (!DailyTaskList::getInstance()->isUserLogined()) {  //登录态验证
    $response['code'] = ErrorCode::User_Not_Login;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    //echo json_encode($response);
    echo print_r($response['data']);
	exit();
}

/* 用户已登录，显示具体任务信息 */
$completed_task = DailyTaskList::getInstance()->getUserCompletedTask($uin);  //已完成任务集
if (count($completed_task) != 0) {  
    foreach($completed_task as $task){  //修改已完成任务任务次数字段
        $index=array_search($task['task_id'],$all_id);
        $response['data'][$index]['completed_times'] = $task['task_times'];
    }
}

echo json_encode($response);
exit();