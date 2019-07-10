<?php
/**
 * User: ASUS
 * Date: 2019/4/8
 * Time: 18:16
 *
 * 助力详细页面
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';

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

// 是否登录
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
if (!is_numeric($uin) || !$uin) {
    $result['code'] = 5101;
    $result['msg'] = "error params";
    echo json_encode($result);
    exit;
}
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $response['code'] =	ErrorCode::User_Not_Login;
    $response['msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

// 获得代金券对应的主键id记录
$seleId = isset($_REQUEST['seleId']) && is_numeric($_REQUEST['seleId']) ? $_REQUEST['seleId'] : 0;
if (!$seleId) {
    $result['msg'] = 'get seleId error';
    $result['code'] = 5119;
    echo json_encode($result);exit;
}

$obj = new Db();
$obj->use_db('read');
$sql = "SELECT * FROM ll_51_help_voucher_select WHERE id=$seleId";
$seleData = $obj->query($sql);

if (empty($seleData)) {
    $result['msg'] = 'no this rows';
    $result['code'] = 51190;
    echo json_encode($result);exit;
}

// 显示代金券信息
$result['data']['voucherName'] = $helpArr[$seleData[0]['voucher_id'] - 1]['name'];
$result['data']['helpTimes'] = $helpArr[$seleData[0]['voucher_id'] - 1]['help_times'];
$result['data']['image'] = $helpArr[$seleData[0]['voucher_id'] - 1]['image'];

// 剩余时间
if ($seleData[0]['status'] == 0) {
    // 正在助力，才计算时间
    if (date('H', $seleData[0]['select_date']) >= 21 ) {
        $result['data']['remainTime'] = strtotime(date('Y-m-d', $seleData[0]['select_date'])) + 86400 - time();
    } else {
        $result['data']['remainTime'] = $seleData[0]['select_date'] + 10800 - time();
    }
    if ($result['data']['remainTime'] <= 0) {
        $result['data']['remainTime'] = 0;
        // 时间用完，更改status
        $sqlUpda = "UPDATE ll_51_help_voucher_select SET status=1 WHERE id=$seleId";
        $obj->use_db('write');
        $obj->query($sqlUpda);
        $status = $obj->db->affected_rows;
        if (!$status) {
            $result['msg'] = '执行错误，请稍后重试';
            $result['code'] = '5103';
            echo json_encode($result);
            logLn("uin: {$uin} update ll_51_help_voucher_select data error");
            exit();
        }
        $seleData[0]['status'] = 1;
    }
} else {
    // 其他状态
    $result['data']['remainTime'] = 0;
}

// 助力次数
$result['data']['friendHelpNum'] = $seleData[0]['friend_help_num'];
if ($result['data']['friendHelpNum'] > $result['data']['helpTimes']) {
    $result['data']['friendHelpNum'] = $result['data']['helpTimes'];
}

// 按钮状态
$result['data']['status'] = $seleData[0]['status'];  // 0正在助力，1失败，2成功

$result['code'] = 1;
echo json_encode($result);exit;