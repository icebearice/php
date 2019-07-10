<?php
/*
 * 暑期活动，我的夺宝记录
 *
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/commonFunc.php';
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/REDIS.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/voucherServer.class.php';
require_once SYSDIR_UTILS . '/GPVoucherServer.class.php';

$result = array(
    'code' => 0,
    'msg' => '',
    'data' => array(),
);

//1.0 检查登录
$uin = checkLogin();
$systemType = getSystemType();
if (!$uin) {
    $response['code'] = ErrorCode::User_Not_Login;
    $response['msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$obj = new Db();
$obj->use_db('read');
$sql = "SELECT * FROM ll_summer_fight_gem WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
$data = $obj->query($sql);
$res = array();
if ($data) {
    if ($systemType==1) {
        $voucherObj = new GPVoucherServer(); 
    } else {
        $voucherObj = new LLVoucherServer();
    }
    foreach($data as $k => $v) {
        $fightInfo = getFightInfo($systemType, $v['prize_id']);
        if (!$fightInfo) {
            continue; //这种情况，正常下不会出现
        }
        if ($fightInfo['type'] == 2) { //代金券
            $voucherInfo = $voucherObj->getVoucherInfo($fightInfo['num']);
        }
        $res[date('Y.m.d', strtotime($v['add_time']))][] = array(
            'name' => $fightInfo['name'],
            'add_time' => $v['add_time'],
            'status' => $v['status'],
            'type' => $fightInfo['type'],
            'price' => $fightInfo['type']==2 ? $voucherInfo['money'] : $v['num'],
            'limit' => $fightInfo['type']==2 ? $voucherInfo['min_order_amount'] : 0,
        );
    }
}
$result['data'] = $res;
echo json_encode($result);
exit;
