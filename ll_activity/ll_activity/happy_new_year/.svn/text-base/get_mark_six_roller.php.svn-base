<?php
/*************************************************************************
* File Name: get_mark_six_roller.php
* Author: lvchao.yan
* Created Time: 2018.12.22
* Desc: 显示用户六合彩滚筒信息信息(页面载入或刷新)
************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/MarkSexRoller.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

checkActivityDate();

$response = array(
    "code" => 0,
    "err_msg" => '',
    "data" => '',
);

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

$response['data']['is_completed_2019'] = 0;
$go_times = MarkSexRoller::getInstance()->getUserGoTimes($uin);
$response['data']['go_times'] = $go_times;
$num_count = MarkSexRoller::getInstance()->getUserCollectNumCount($uin);  //index为收集的数字，value为该数字的个数

$num = array();  //收集的数字集合
foreach($num_count as $key => $value){
	if($value) $num[] = $key;  //值不为0,取键
}
if (in_array(2,$num)&&in_array(0,$num)&&in_array(1,$num)&&in_array(9,$num)) {  //判断是否集齐2019
    $response['data']['is_completed_2019'] = 1;
}

$all_num = array();  //0-9
for($i = 0; $i < 10; $i ++) $all_num[] = 0; //初始值
foreach($num_count as $key => $value){  //修改收集的数字为具体值
	$all_num[$key] = $value;
}
$response['data']['num_count'] = $all_num;  //写入收集的数字信息

//print_r($response['data']);exit();
echo json_encode($response);
exit();
