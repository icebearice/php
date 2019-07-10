<?php
/*
 * 2019暑期活动，用户签到接口
 *
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/commonFunc.php';
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_INCLUDE . '/global.php';

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

//3.0 签到判定
$sid = isset($_REQUEST['sid']) ? $_REUQEST['sid'] : 0;
if (!$sid) {
    $response['code'] = 6001;
    $response['msg'] = '未知数据错误'; 
    echo json_encode($response);
    exit();
}
$obj = new Db();
$obj->use_db('read');
$signInfo = getSignInfo();
if (!isset($signInfo[$sid])) {
    $response['code'] = 6001;
    $response['msg'] = '未知数据错误'; 
    echo json_encode($response);
    exit();
}
$date = date('Ymd');
if ($signInfo[$sid]['signTime'] > $date) {
    $response['code'] = 6001;
    $response['msg'] = '尚未到签到时间，再耐心等等啦~';
    echo json_encode($response);
    exit();
}
$sql = "SELECT id FROM ll_summer_sign_in WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND sid = '{$sid}'";
$data = $obj->query($sql);
if ($data) {
    $response['code'] = 6001;
    $response['msg'] = '您已签到,明日记得再来~';
    echo json_encode($response);
    exit();
}

//4.0 开始签到
$isRepair = false;
if ($signInfo[$sid]['signTime'] < $date) {
    //补签 
    $isRepair = true;
}
$response = array(
    'code' => 6001,
    'msg' => '签到失败，请重试',
);

//4.1 签到日志
$obj->query('start transaction');
$insertSignInArr = array(
    'uid' => $uin,
    'system_tyep' => $systemType,
    'sid' => $sid,
    'get_num' => $signInfo[$sid]['num'],
    'expend_num' => $isRepair ? 1 : 0,
    'sign_time' => date('Y-m-d H:i:s'),
);
$obj->insert('ll_summer_sign_in', $insertSignInArr);
if ($obj->db->affected_rows <= 0) {
    $obj->query('rollback');
    echo json_encode($response);
    exit();
}

//4.2 任务日志
$insertUniqArr = array(
    'uid' => $uin,
    'system_type' => $systemType,
    'date' => $date,
    'task_id' => 6, //签到任务id是6
    'add_time' => date('Y-m-d H:i:s'),
    'num' => $signInfo[$sid]['num'],
    'ext_mark' => getIp(),
);
$obj->insert('ll_summer_task_uniq', $insertUniqArr);
if ($obj->db->affected_rows <= 0) {
    $obj->query('rollback');
    echo json_encode($response);
    exit();
}

//4.3 到账
$sql = "UPDATE ll_summer_user_info SET total_num = total_num + {$signInfo[$sid]['num']}, num = num + {$signInfo[$sid]['num']} WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
$obj->query($sql);
if ($obj->db->affected_rows <= 0) {
    $obj->query('rollback');
    echo json_encode($response);
    exit();
}

if (!$obj->query('commit')) {
    $obj->query('rollback');
    echo json_encode($response);
    exit();
}

$response['code'] = 0;
$response['msg'] = '签到成功';
echo json_encode($response);
exit();
