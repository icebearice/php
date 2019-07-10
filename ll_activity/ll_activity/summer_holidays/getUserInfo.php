<?php
/*
 * 获取暑期活动，用户参与活动的相关信息
 * 2019-06-25
 *
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/commonFunc.php';
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/REDIS.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/GPUserInfoServer.class.php';

$result = array(
    'code' => 0,
    'msg' => '',
    'data' => array(
        'signInfo' => array(),
        'lotteryStatus' => 0, 
        'fightInfo' => array(),
        'taskInfo' => array(),
        'summerValue' => 0,
    ),
);

//1.0 活动时间检测，活动结束用户依然可以看到信息
$res = isActivityTime();
if ($res > 0) {
    $response['code'] = 517;
    $response['msg'] = '活动将于7月26日正式开始,感谢您的关注！'; 
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

//3.0 果盘活动资格判定
if ($systemType == 1) {
    $userObj = new GPUserInfoServer();
    $userInfo = $userObj->getUser($uin); 
    if (!$userInfo) { //这种情况基本只能是服务挂了
        $response['code'] = 6001;
        $response['msg'] = '本活动抽取幸运玩家参与，不要灰心，请期待下次活动哟~';
        echo json_encode($response);
        exit();
    }
    $obj = new Db();
    $obj->use_db('developer');
    $sql = "SELECT is_self FROM channel WHERE cid = '{$user['cid']}'";
    $channel = $obj->query($sql);
    if (!$channel) { //这种情况就诡异了
        addLog($uin, $userInfo, $channel);
        $response['code'] = 6002;
        $response['msg'] = '本活动抽取幸运玩家参与，不要灰心，请期待下次活动哟~';
        echo json_encode($response);
        exit();
    }
    if (!in_array($channel[0]['is_self'], array(1, 2, 3))) {
        addLog($uin, $userInfo, $channel);
        $response['code'] = 6003;
        $response['msg'] = '本活动抽取幸运玩家参与，不要灰心，请期待下次活动哟~';
        echo json_encode($response);
        exit();
    }
}

//3.0 标记用户参与活动，加入队列，异步程序去检测用户的任务完成情况并到账,只有活动期间需要
$obj = new Db();
$obj->use_db('read');
if ($res === 0) {
    $data = array(
        'uid' => $uin,
        'systeType' => $systemType,
        'addTime' => time(),
    );
    $redisObj = new myRedis();
    $redisObj->use_redis('write');
    $key = "LL_TASK_UNIQ_{$uin}";
    if (!$redisObj->redis->get($key)) {
        $redisObj->redis->set($key, $uin, 60); //1分钟内只会记一次
        $redisObj->redis->rpush('LL_SUMMER_HOLIDAYS_TASK', json_encode($data));
    }
    $sql = "SELECT uid FROM ll_summer_user_info WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
    $data = $obj->query($sql);
    if (!$data) {
        $insertArr = array(
            'uid' => $uin,
            'system_type' => $systemType,
            'total_num' => 0,
            'num' => 0,
            'ip' => getIp(),
            'add_time' => date('Y-m-d H:i:s'),
        );
        $obj->insert('ll_summer_user_info', $insertArr); //这里只是去初始化一条数据，不管成功与否
    }
}

//4.0 签到信息
$signInfo = getSignInfo();
$sql = "SELECT uid, system_type, sid, expend_num FROM ll_summer_sign_in WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
$data = $obj->query($sql);
$userSigns = array();
if ($data) {
    foreach($data as $v) {
        $userSigns["{$v['sid']}"] = $v;
    }
}
$date = date('Ymd');        
foreach($signInfo as $k => $v) {
    if (isset($userSigns[$v['id']])) {
        $signInfo[$k]['status'] = 1; //已经签到
    } else {
        if ($v['signTime'] == $date) {
            $signInfo[$k]['status'] = 2; //未签到
        } else if ($v['signTime'] > $date) {
            $signInfo[$k]['status'] = 3; //还未到签到时间
        } else if ($v['signTime'] < $date) {
            $signInfo[$k]['status'] = 4; //可补签
        }
    }
    unset($signInfo[$k]['signTime']);
    unset($signInfo[$k]['num']);
}
$result['data']['signInfo'] = $signInfo;

//5.0 抽奖信息
$sql = "SELECT id FROM ll_summer_lottery WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND date = '{$date}'";
$data = $obj->query($sql);
$result['data']['lotteryStatus'] = $data ? 1 : 0;
$sql = "SELECT name, icon, ptype, kid FROM ll_summer_gift WHERE system_type IN ('{$systemType}', 0) AND date = '{$date}' ORDER BY kid ASC";
$data = $obj->query($sql);
$result['data']['lotteryInfo'] = $data;

//6.0 夺宝信息
$nowDate = date('Y-m-d H:i:s');
$sql = "SELECT id, end_time FROM ll_summer_fight_periods WHERE start_time <= '{$nowDate}' AND end_time >= '{$nowDate}' ORDER BY id DESC LIMIT 1";
$periodsInfo = $obj->query($sql);
$pid = $periodsInfo ? $periodsInfo[0]['id'] : 0;
if ($pid > 0) {
    $fightInfo = getFightInfo($systemType);
    $sql = "SELECT id, prize_id FROM ll_summer_fight_gem WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND periods_id = '{$pid}'"; 
    $data = $obj->query($sql);
    $userFight = array();
    if ($data) {
        foreach ($data as $k => $v) {
            $userFight[$v['prize_id']] = $v;
        }
    }
    foreach($fightInfo as $k => $v) {
        $fightInfo[$k]['userStatus'] = isset($userFight[$v['id']]) ? 1 : 0;
        $fightInfo[$k]['endTime'] = date('m-d H:i', strtotime($periodsInfo[0]['end_time'])+1);
        unset($fightInfo[$k]['num']);
        unset($fightInfo[$k]['type']);
        unset($fightInfo[$k]['hitNum']);
        unset($fightInfo[$k]['systemType']);
    }
    $result['data']['fightInfo'] = $fightInfo;
}

//7.0 任务信息
$taskInfo = getTaskInfo();
$sql = "SELECT task_id FROM ll_summer_task_uniq WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND date = '{$date}'";
$data = $obj->query($sql);
$userTask = array();
if ($data) {
    foreach($data as $k => $v) {
        $userTask[$v['task_id']] = $v;
    }
}
foreach($taskInfo as $k => $v) {
    if ($v['systemType'] && $v['systemType'] != $systemType) {
        unset($taskInfo[$k]);
        continue;
    }
    $taskInfo[$k]['userStatus'] = 1;
    if ($v['check']) {
        $taskInfo[$k]['userStatus'] = isset($userTask[$v['id']]) ? 1 : 0;
    }
    unset($taskInfo[$k]['num']);
    unset($taskInfo[$k]['check']);
    unset($taskInfo[$k]['systemType']);
}
$result['data']['taskInfo'] = $taskInfo;

//8.0 清凉值
$sql = "SELECT num FROM ll_summer_user_info WHERE uid = '{$uin}' AND system_type = '{$systemType}'";
$data = $obj->query($sql);
$result['data']['summerValue'] = $data ? $data[0]['num'] : 0;
echo json_encode($result);
exit;
