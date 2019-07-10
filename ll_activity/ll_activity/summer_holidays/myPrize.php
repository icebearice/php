<?php
/*
 * 暑期活动，我的奖品
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
$sql = "SELECT * FROM ll_summer_gift WHERE system_type IN ('{$systemType}', 0)"; 
$data = $obj->query($sql);
$lotteryPrize = array();
if ($data) {
    foreach($data as $v) {
        $lotteryPrize[$v['date']][$v['id']] = $v;
    }
}


$sql = "SELECT * FROM ll_summer_my_gift WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
$data = $obj->query($sql);
$res = array();
if ($data) {
    if ($systemType==1) {
        $voucherObj = new GPVoucherServer(); 
    } else {
        $voucherObj = new LLVoucherServer();
    }
    foreach($data as $k => $v) {
        if ($v['get_type'] == 2) { //夺宝
            $info = getFightInfo($systemType, $v['lid']);
        } else {
            $info = $lotteryPrize[date('Ymd', strtotime($v['get_time']))][$v['lid']];
        }
        if (!$info) {
            continue; //这种情况，正常下不会出现
        }
        $type = $v['get_type']==2 ? $info['type'] : $info['ptype'];
        if ($type == 2) { //代金券
            $num = $v['get_type']==2 ? $info['num'] : $info["ext_num_{$v['params_ext']}"];
            $voucherInfo = $voucherObj->getVoucherInfo($num);
        }
        $res[date('Y.m.d', strtotime($v['add_time']))][] = array(
            'name' => $info['name'],
            'add_time' => $v['add_time'],
            'status' => $v['status'],
            'type' => $type,
            'price' => $type==2 ? $voucherInfo['money'] : $v['num'],
            'limit' => $type==2 ? $voucherInfo['min_order_amount'] : 0,
        );
    }
}
$result['data'] = $res;
echo json_encode($result);
exit;
