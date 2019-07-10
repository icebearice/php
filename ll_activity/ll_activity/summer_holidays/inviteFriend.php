<?php
/*
 * 暑期活动邀请好友页面逻辑
 * 2019-06-25
 *
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/commonFunc.php';
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/REDIS.php';
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

$uin = isset($_REQUEST['uin'])&&is_numeric($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$systemType = getSystemType();
if ($uin <= 0 || $systemType <= 0) {
    $response['code'] = 6001;
    $response['msg'] = '未知助力用户';
    echo json_encode($response);
    exit;
}

//2.0 查看当前ip是否已打开这个页面，没有的话要给分享者加清凉值
$ip = getIp();
$obj = new Db();
$obj->use_db('read');
$sql = "SELECT ip, friend_num FROM ll_summer_user_info WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
$share = $obj->query($sql);
if (!$share) {
    $response['code'] = 6002;
    $response['msg'] = '未知助力用户';
    echo json_encode($response);
    exit;
}
$sql = "SELECT id FROM ll_summer_share_log WHERE invite_ip = '{$ip}' AND ac_type = 1";
$data = $obj->query($sql);
if (!$data && $ip != $share[0]['ip']) { //如果ip是自己，则不加
    $insertArr = array(
        'uid' => $uin,
        'system_type' => $systemType,
        'invite_ip' => $ip,
        'ac_type' => 1,
        'add_time' => date('Y-m-d H:i:s'), 
    );
    $obj->use_db('write');
    $obj->query('start transaction');
    $obj->insert('ll_summer_share_log', $insertArr);
    if ($obj->db->affected_rows <= 0) {
        $obj->query('rollback');
    } else {
        $sql = "UPDATE ll_summer_user_info SET total_num = total_num + 3, num = num + 3  WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
        $obj->query($sql);
        if ($obj->db->affected_rows <= 0) {
            $obj->query('rollback');
        } else {
            $obj->query('commit');
        }
    }
}

//3.0 发起点赞的请求还是返回数据请求
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$sql = "SELECT id FROM ll_summer_share_log WHERE invite_ip = '{$ip}' AND ac_type = 2";
$obj->use_db('read');
$data = $obj->query($sql);
$like = $data ? 1 : 0;
if ($action == 'add') {
    if ($like) {
        $response['code'] = 6003;
        $response['mgs'] = '您已点赞过哦'; 
        echo json_encode($resopnse);
        exit;
    }
    if ($ip == $share[0]['ip']) {
        $response['code'] = 6004;
        $response['mgs'] = '不能给自己点赞哦'; 
        echo json_encode($resopnse);
        exit;
    }
    $insertArr = array(
        'uid' => $uin,
        'system_type' => $systemType,
        'invite_ip' => $ip,
        'ac_type' => 2,
        'add_time' => date('Y-m-d H:i:s'), 
    );
    $obj->use_db('write');
    $obj->query('start transaction');
    $obj->insert('ll_summer_share_log', $insertArr);
    if ($obj->db->affected_rows <= 0) {
        $obj->query('rollback');
        $response['code'] = 6005;
        $response['mgs'] = '点赞失败，请重试'; 
        echo json_encode($resopnse);
        exit;
    }
    $sql = "UPDATE ll_summer_user_info SET total_num = total_num + 2, num = num + 2, friend_num = friend_num + 2 WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
    $obj->query($sql);
    if ($obj->db->affected_rows <= 0) {
        $obj->query('rollback');
        $response['code'] = 6006;
        $response['mgs'] = '点赞失败，请重试'; 
        echo json_encode($resopnse);
        exit;
    }
    if (!$obj->query('commit')) {
        $response['code'] = 6007;
        $response['mgs'] = '点赞失败，请重试'; 
        echo json_encode($resopnse);
        exit;
    }
    $response['code'] = 0;
    $response['msg'] = '点赞成功';
    echo json_encode($response);
    exit;
}
$response['code'] = 0;
$response['data']['is_like'] = $like;
$response['data']['friend_summer_value'] = $share[0]['friend_num'];
echo json_encode($response);
