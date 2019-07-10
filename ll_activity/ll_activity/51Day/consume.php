<?php
/**
 * User: ASUS
 * Date: 2019/4/8
 * Time: 13:34
 *
 * 消费送豪礼 领取任务接口
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
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

// 不登录也展示
$result['data']['voucherList'] = $consumeArr;

// 是否登录
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;

$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    // 以下前端要求标识动作
    if (!empty($_GET['receiveTen']) || !empty($_GET['receiveHun']) || !empty($_GET['hunVoucherID'])) {
        echo json_encode(array('code'=>503, 'msg'=>'您还没有登录'));
        exit;
    }
    $result['code'] = 1;
    echo json_encode($result);exit;
}

// 已登录
$obj = new Db();
$obj->use_db('read');

// 任务相关信息
$sqlGet = "SELECT * FROM ll_51_consume_log WHERE uin=" . $uin;
$consData = $obj->query($sqlGet);
$result['data']['isReceiveTen'] = isset($consData[0]['get_job_ten_date']) && !empty($consData[0]['get_job_ten_date']) ? 1 : 0;
$result['data']['isReceiveHun'] = isset($consData[0]['get_job_hundred_date']) && !empty($consData[0]['get_job_hundred_date']) ? 1 : 0;
$result['data']['isFinishTen'] = isset($consData[0]['fin_job_ten_date']) && !empty($consData[0]['fin_job_ten_date']) ? 1 : 0;
$result['data']['isFinishHun'] = isset($consData[0]['fin_job_hundred_date']) && !empty($consData[0]['fin_job_hundred_date']) ? 1 : 0;
$result['data']['getHunVoucherID'] = isset($consData[0]['get_job_hundred_voucher_id']) && !empty($consData[0]['get_job_hundred_voucher_id']) ? $consData[0]['get_job_hundred_voucher_id'] : 0;


$obj->use_db('write');
$insertArr = array();
$updaArr = array();
// 每次请求只有一个动作
// 领取任务接口标识
if(isset($_GET['receiveTen']) && $_GET['receiveTen'] == 'receiveTen') {
    if ($result['data']['isReceiveTen'] == 1) {
        echo json_encode(array('code'=>1, 'msg'=>'已领取'));exit;
    }

    if ($result['data']['isReceiveHun'] == 1) {
        // 此前有记录，update
        $updaArr = array('get_job_ten_date' => time());
        $obj->update('ll_51_consume_log', $updaArr, ['uin'=>$uin]);
        $status = $obj->db->affected_rows;
    } else {
        // 无记录，insert
        $insertArr = array('uin' => $uin, 'get_job_ten_date' => time());
        $obj->insert('ll_51_consume_log', $insertArr);
        $status = $obj->db->affected_rows;
    }
    if (!$status) {
        $result['code'] = 5109;
        $result['msg'] = "sql receiveTen error";
        logLn("uin:{$uin} sql receiveTen error");
        echo json_encode($result);
        exit;
    }
    $res['code'] = 1;
    $res['msg'] = 'success';
    echo json_encode($res);exit;
}

if (isset($_GET['receiveHun']) && $_GET['receiveHun'] == 'receiveHun') {
    if ($result['data']['isReceiveHun'] == 1) {
        echo json_encode(array('code'=>1, 'msg'=>'已领取'));exit;
    }

    if ($result['data']['isReceiveTen'] == 1) {
        // 此前有记录，update
        $updaArr = array('get_job_hundred_date' => time());
        $obj->update('ll_51_consume_log', $updaArr, ['uin'=>$uin]);
        $status = $obj->db->affected_rows;
    } else {
        // 无，insert
        $insertArr = array('uin' => $uin, 'get_job_hundred_date' => time());
        $obj->insert('ll_51_consume_log', $insertArr);
        $status = $obj->db->affected_rows;
    }
    if (!$status) {
        logLn("uin:{$uin} sql receiveHun error");
        $result['code'] = 5110;
        $result['msg'] = "sql receiveHun error";
        echo json_encode($result);
        exit;
    }
    $res['code'] = 1;
    $res['msg'] = 'success';
    echo json_encode($res);exit;
}

// 任务完成领取奖励接口动作
if (isset($_GET['hunVoucherID']) && is_numeric($_GET['hunVoucherID'])) {
    if ($result['data']['isFinishHun']  != 1) {
        echo json_encode(array('code'=>1, 'msg'=>'您还未完成任务'));exit;
    }
    if (!empty($result['data']['getHunVoucherID'])) {
        echo json_encode(array('code'=>1, 'msg'=>'已领取'));exit;
    }
    // 校验id是否在1-5范围内
    if ($_GET['hunVoucherID'] < 1 || $_GET['hunVoucherID'] > 5) {
        $result['code'] = 5114;
        $result['msg'] = "hunVoucherID not in 1-5 error";
        echo json_encode($result);
        exit;
    }

    $obj->query('start transaction');
    $updaArr = array('get_job_hundred_voucher_id' => $_GET['hunVoucherID'], 'get_job_hundred_voucher_date' => time());
    $obj->update('ll_51_consume_log', $updaArr, ['uin' => $uin]);
    $updaRows = $obj->db->affected_rows;
    if (!$updaRows) {
        $obj->query('rollback');
        logLn("uin:{$uin} sql hunVoucherID error");
        $result['code'] = 5113;
        $result['msg'] = "sql hunVoucherID error";
        echo json_encode($result);
        exit;
    }
    // 下发用户选择的 id 代金券，通过id得到voucher_id，然后调用
    $voucherId = $consumeArr[$_GET['hunVoucherID']-1]['voucher_id'];
    $voucherObj = new LLVoucherServer();
    $pushRes = $voucherObj->sendVoucher($uin, 'test-flamingo-login-key-abc', 'adc', 136, 102, $voucherId, 0);
    if (!$pushRes) {
        $obj->query('rollback');
        logLn("uin:{$uin} sendVoucher error");
        echo json_encode(array('code' => 51071, 'msg' => 'push voucher error'));
        exit;
    }
    $obj->query('commit');

    $res['code'] = 1;
    $res['msg'] = 'success';
    echo json_encode($res);
    exit;
}

$result['code'] = 1;
echo json_encode($result);