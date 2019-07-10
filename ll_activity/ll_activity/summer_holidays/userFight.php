<?php
/*
 * 暑期活动，用户夺宝
 *
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/commonFunc.php';
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/REDIS.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/VIPInfo.php';
require_once SYSDIR_UTILS . '/GPVipInfo.php';

$result = array(
    'code' => 0,
    'msg' => '',
    'data' => array(),
);

//1.0 活动时间检测，活动结束用户依然可以看到信息
$res = isActivityTime();
if ($res > 0) {
    $response['code'] = 517;
    $response['msg'] = '活动将于7月26日正式开始,感谢您的关注！'; 
    echo json_encode($response);
    exit();
}
if ($res < 0) {
    $response['code'] = 516;
    $response['msg'] = '活动已于8月4日结束,感谢您的关注！'; 
    echo json_encode($response);
    exit();
}

//2.0 检查登录
$uin = checkLogin();
$systemType = getSystemType();
if (!$uin) {
    $response['code'] = ErrorCode::User_Not_Login;
    $response['msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

//3.0 夺宝资格检查
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$info = getFightInfo($systemType, $id) ;
if (!$id || !$info) {
    $response['code'] = 6001;
    $response['msg'] = '未知夺宝信息，请重试'; 
    echo json_encode($response);
    exit();
}
if ($systemType==1) {
    $vipObj = new GPVIPInfo();
} else {
    $vipObj = new VIPInfo();
}
$vipLevel = $vipObj->getVipLevel($uin);
if ($vipLevel < $info['limitVip']) {
    $response['code'] = 6002;
    $response['msg'] = '该奖品仅对部分VIP开放，您差一点能达到啦，快去提升VIP等级吧'; 
    echo json_encode($response);
    exit();
}
$obj = new Db();
$obj->use_db('read');
$nowDate = date('Y-m-d H:i:s');
$sql = "SELECT id FROM ll_summer_fight_periods WHERE start_time <= '{$nowDate}' AND end_time >= '{$nowDate}' ORDER BY id DESC LIMIT 1";
$periodsInfo = $obj->query($sql);
$pid = $periodsInfo ? $periodsInfo[0]['id'] : 0; //第几期的夺宝
if (!$pid) {
    $response['code'] = 6003;
    $response['msg'] = '当前不是夺宝时间'; 
    echo json_encode($response);
    exit();
}
$sql = "SELECT id FROM ll_summer_fight_gem WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND periods_id = '{$pid}' AND prize_id = '{$id}'";
$data = $obj->query($sql);
if ($data) {
    $response['code'] = 6004;
    $response['msg'] = '您已参与该奖品的夺宝，请耐心等待开奖'; 
    echo json_encode($response);
    exit();
}
$sql = "SELECT num FROM ll_summer_user_info WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
$user = $obj->query($sql);
if (!$user || $user[0]['num'] < $info['spendNum']) {
    $response['code'] = 6008;
    $response['msg'] = '清凉值不足，快去获取清凉值吧'; 
    echo json_encode($response);
    exit();
}

//4.0 开始入库数据
$obj->use_db('write');
$obj->query('start transaction');
$insertArr = array(
    'uid' => $uin,
    'system_type' => $systemType,
    'periods_id' => $pid,
    'prize_id' => $id,
    'add_time' => date('Y-m-d H:i:s'),
);
$obj->insert('ll_summer_fight_gem', $insertArr);
if ($obj->db->affected_rows <= 0) {
    $obj->query('rollback');
    $response['code'] = 6005;
    $response['msg'] = '夺宝失败,请重试'; 
    echo json_encode($response);
    exit();
}
$sql = "UPDATE ll_summer_user_info SET num = num - '{$info['spendNum']}' WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND num >= '{$info['spendNum']}'";
$obj->query($sql);
if ($obj->db->affected_rows <= 0) {
    $obj->query('rollback');
    $response['code'] = 6006;
    $response['msg'] = '夺宝失败,请重试'; 
    echo json_encode($response);
    exit();
}
if (!$obj->query('commit')) {
    $response['code'] = 6007;
    $response['msg'] = '夺宝失败,请重试'; 
    echo json_encode($response);
    exit();
}

$response['code'] = 0;
$response['msg'] = '';
echo json_encode($response);
exit();
