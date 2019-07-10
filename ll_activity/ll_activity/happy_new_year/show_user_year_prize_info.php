<?php
/*************************************************************************
* File Name: show_user_year_prize_info.php
* Author: lvchao.yan
* Created Time: 2018.12.20
* Desc: 显示用户年货信息(页面载入或刷新)
************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/AcceptYearPrize.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

define ("CAN_ACCEPT", 1);
define ("ALREADY_ACCEPT", 2);
define ("EXHAUSTED", 3);
define ("LOCKED", 4);

checkActivityDate(1);

$response = array(
    "code" => 0,
    "err_msg" => '',
    "data" => '',
);

$prize = AcceptYearPrize::getInstance()->showYearPrize();  //所有年货信息
$all_id = array();  //所有年货id集
foreach($prize as $p){  
    $all_id[]=$p['prize_id'];
    $response['data'][] = array( 'prize_id'=>$p['prize_id'],'prize_count'=>intval($p['prize_name']),'prize_type'=>intval($p['prize_type']),'prize_vip_level'=>intval($p['prize_vip_level']),'prize_icon'=>$p['prize_icon'],'prize_status'=>CAN_ACCEPT);
}
/* 用户未登录，所有年货状态皆为CAN_ACCRPT */
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

/* 用户已登录，显示具体年货信息 */
$accept_id = AcceptYearPrize::getInstance()->getUserAcceptPrizeId($uin);  //已领取年货id集
if (count($accept_id) != 0) {  
    foreach($accept_id as $id){  //将已领取年货状态位置ALREADY_ACCEPT
        $index=array_search($id,$all_id);
        $response['data'][$index]['prize_status'] = ALREADY_ACCEPT;
    }
}

$not_accept_id=array_diff($all_id,$accept_id);  //未领取年货id集
if (count($not_accept_id) == 0) {  //全部年货已领取，退出
    echo json_encode($response);
    exit();
}

$exhausted_id = array();  //未领取年货里已领完年货id集
foreach($not_accept_id as $id){  //将已领完年货状态位置EXHAUSTED
    $index = array_search($id,$all_id);
    if (intval($prize[$index]['prize_total']) <= 0) {
        $exhausted_id[] = $id;
        $response['data'][$index]['prize_status'] = EXHAUSTED;
    }
}

$unexhuasted_id = array_diff($not_accept_id,$exhausted_id);  //未领取且未领完年货id集
if (count($unexhuasted_id) == 0) {  //未领取年货已全部领完，退出
    echo json_encode($response);
    exit();
}

foreach($unexhuasted_id as $id){  //将VIP等级不够年货状态位置LOCKED
    $index = array_search($id,$all_id);
    $prize_vip_level = $prize[$index]['prize_vip_level'];
    $user_vip_level = UserInfo::getInstance()->getVipLevel($uin); 
    //$user_vip_level = 5;
    if ($user_vip_level < $prize_vip_level) {
        $response['data'][$index]['prize_status'] = LOCKED;
    }
}

//print_r($response);exit();
echo json_encode($response);
exit();
