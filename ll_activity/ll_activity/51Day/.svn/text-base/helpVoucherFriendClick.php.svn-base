<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2019/4/9
 * Time: 12:01
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/XXRequestBase.php';
require_once SYSDIR_UTILS . '/voucherServer.class.php';

$result = array(
    'code' => -1,
    'msg' => '',
    'data' => array(),
);

//是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    $result['msg'] = $time['msg'];
    echo json_encode($result);exit;
}

// 获得代金券对应的主键id记录
$seleId = isset($_REQUEST['seleId']) && is_numeric($_REQUEST['seleId']) ? $_REQUEST['seleId'] : 0;
if (!$seleId) {
    $result['msg'] = 'get seleId error';
    $result['code'] = 5120;
    echo json_encode($result);exit;
}

$obj = new Db();
$obj->use_db('read');
$sql = "SELECT status,friend_help_num,voucher_id,select_date,uin,myip FROM ll_51_help_voucher_select WHERE id=$seleId";
$seleData = $obj->query($sql);
if (empty($seleData)) {
    echo json_encode(array('code'=>51200, 'msg'=>'no this rows'));exit;
}
$uin = $seleData[0]['uin'];
if ($seleData[0]['status'] == 1) {
    $result['msg'] = '该用户完成助力失败了';
    $result['code'] = 5121;
    echo json_encode($result);exit;
}
if ($seleData[0]['status'] == 2) {
    $result['msg'] = '哎呀，刚刚已有其他人帮忙完成了助力';
    $result['code'] = 5121;
    echo json_encode($result);exit;
}

// 校验正在助力的剩余时间
if (date('H', $seleData[0]['select_date']) >= 21) {
    $remainTime = strtotime(date('Y-m-d', $seleData[0]['select_date'])) + 86400 - time();
} else {
    $remainTime = $seleData[0]['select_date'] + 10800 - time();
}
if ($remainTime <= 0) {
    // 时间用完，更改status
    $sqlUpda = "UPDATE ll_51_help_voucher_select SET status=1 WHERE id=$seleId";
    $obj->use_db('write');
    $obj->query($sqlUpda);
    $result['msg'] = '时间用完了哦';
    $result['code'] = 5122;
    echo json_encode($result);exit;
}

// 点击助力动作
// 自己不可以给自己助力
$ip = getIp();
if (!isset($seleData[0]['myip']) || $seleData[0]['myip'] == $ip) {
    $result['msg'] = '不能给自己助力哦';
    $result['code'] = 51210;
    echo json_encode($result);exit;
}

//校验助力IP，同一IP，每日只可助力1次
$today = date('Y-m-d', time());
$sqlHelp = "SELECT id FROM ll_51_help_voucher_log WHERE help_info='{$ip}' and help_date='{$today}' and type=1";
$helpData = $obj->query($sqlHelp);
if (count($helpData) >= 1) {
    $result['msg'] = '今日助力机会已用完，不能再助力了哦';
    $result['code'] = 5121;
    echo json_encode($result);exit;
}

//开启事务
$obj->use_db('write');
$obj->query('start transaction');
// 助力成功，看select表中friend_help_num是否够助力次数
$friend_help_num = $seleData[0]['friend_help_num'] + 1;
$help_times = $helpArr[$seleData[0]['voucher_id']-1]['help_times'];
$isTimesEnough = 0; // 前端要求标识每次点击是否成功完成助力
if ($friend_help_num >= $help_times) {
    // 修改status和friend_help_num
    $sqlUpda = "UPDATE ll_51_help_voucher_select SET status=2,friend_help_num=$friend_help_num WHERE id=$seleId";
    $obj->query($sqlUpda);
    $updaRows = $obj->db->affected_rows;
    if (!$updaRows) {
        $obj->query('rollback');
        logLn("uin:{$uin} sql excute error：$sqlUpda");
        echo json_encode(array('code' => 51070, 'msg' => '操作频繁'));exit;
    }
    // 下发代金券
    $voucherId = $helpArr[$seleData[0]['voucher_id']-1]['voucher_id'];
    $voucherObj = new LLVoucherServer();
    $pushRes = $voucherObj->sendVoucher($uin, 'test-flamingo-login-key-abc', 'adc', 136, 102, $voucherId, 0);
    if (!$pushRes) {
        $obj->query('rollback');
        logLn("uin:{$uin} sendVoucher error");
        echo json_encode(array('code' => 51071, 'msg' => 'push voucher error'));exit;
    }
    $isTimesEnough = 1;  // 助力成功已够标识

} else {
    // 不够次数，仅修改 friend_help_num
    $sqlUpda = "UPDATE ll_51_help_voucher_select SET friend_help_num=$friend_help_num WHERE id=$seleId";
    $obj->query($sqlUpda);
    $updaRows = $obj->db->affected_rows;
    if (!$updaRows) {
        $obj->query('rollback');
        logLn("uin:{$uin}, sql excute error: $sqlUpda");
        echo json_encode(array('code' => 51070, 'msg' => '操作频繁'));exit;
    }
}

// 写log
$sqlInsr = "INSERT INTO ll_51_help_voucher_log VALUES('', {$seleId}, 1, '{$ip}', '{$today}')";
$obj->query($sqlInsr);
$inseRows = $obj->db->affected_rows;
if (!$inseRows) {
    $obj->query('rollback');
    logLn("uin:{$uin}, sql excute error $sqlInsr");
    echo json_encode(array('code' => 51073, 'msg' => '操作频繁'));exit;
}
$obj->query('commit');

$result['msg'] = 'success';
$result['code'] = '1';
if ($isTimesEnough) {
    $result['code'] = '11';
}
echo json_encode($result);exit;

