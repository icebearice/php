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
require_once SYSDIR_UTILS . "/happynewyear/AcceptYearPrize.class.php";

$response = array(
    "code" => 0,
    "err_msg" => '',
    "data" => '',
);

$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;

if (!AcceptYearPrize::getInstance()->isUserLogined()) {  //登录态验证
    $response['code'] = ErrorCode::User_Not_Login;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
	exit();
}

if (!AcceptYearPrize::getInstance()->isUserBindingPhone($uin)) {  //用户绑定手机验证，待完成.............
    $nowNotBinding = isset($_REQUEST['nowNotBinding'])? $_REQUEST['nowNotBinding'] : 0;
    if (!$nowNotBinding) {  //未绑定手机且第一次访问
        $response['code'] = ErrorCode::User_Not_Binding_Phone;
        $response['err_msg'] = ErrorCode::getTaskError($response['code']);
        echo json_encode($response);
        exit();
    }
}

/* 已绑定手机或暂不绑定 */

$prize = $prize = AcceptYearPrize::getInstance()->showYearPrize();  //所有年货信息
$prizeId = $_REQUEST['prizeId'];  //用户想领取的年货id
foreach($prize as $p){
    if (intval($p['prize_id']) == intval($prizeId)) {
        if (intval($p['prize_total']) <= 0) {  //年货已领完
            $response['code'] = ErrorCode::Year_Prize_Is_Exhausted;
            $response['err_msg'] = ErrorCode::getTaskError($response['code']);
        } else {  //领取年货
            if (AcceptYearPrize::getInstance()->getYearPrize($prizeId)) {
                $response['data']=0;                
            } else {
                $response['code'] = ErrorCode::DataBase_Not_OK;
                $response['err_msg'] = ErrorCode::getTaskError($response['code']);
            }
        }
        echo json_encode($response);
        exit();
    }
}
