<?php
/*************************************************************************
 * File Name: get_year_prize.php
 * Author: lvchao.yan
 * Created Time: 2018.12.21
 * Desc: 用户领取年货 
 *************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/AcceptYearPrize.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

checkActivityDate(1);

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

if (!UserInfo::getInstance()->isUserPhone($uin)) {  //绑定手机验证
	$response['code'] = ErrorCode::User_Not_Phone;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

$prizeId = isset($_REQUEST['prize_id']) ? $_REQUEST['prize_id'] : 0;  //用户想领取的年货id

if($prizeId < 1 || $prizeId > 6){  //年货ID不存在
    $response['code'] = ErrorCode::Year_Prize_ID_Is_Not_Exists;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$accept_prize_id = AcceptYearPrize::getInstance()->getUserAcceptPrizeId($uin);  //已领取年货id集

if(in_array($prizeId,$accept_prize_id)){  //年货已经领取过
    $response['code'] = ErrorCode::Already_Accept;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$prize = $prize = AcceptYearPrize::getInstance()->showYearPrize($prizeId);  //用户领取的年货

if ($prize[0]['prize_total'] <= 0){  //年货已领完
    $response['code'] = ErrorCode::Year_Prize_Is_Exhausted;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$user_vip_level = UserInfo::getInstance()->getVipLevel($uin);
//$user_vip_level = 5; 

if ($user_vip_level < $prize[0]['prize_vip_level']){  //VIP等级不够
    $response['code'] = ErrorCode::VIP_Level_Is_Lower;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$res = AcceptYearPrize::getInstance()->getYearPrize($uin,$prizeId);
if ($res == -1) {
    $response['code'] = ErrorCode::DataBase_Not_OK;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}
$response['data'] = $res;
echo json_encode($response);
exit();
