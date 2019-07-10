<?php
/*
 * 获取暑期活动，处理用户完成任务
 * 2019-06-28
 *
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/commonFunc.php';
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/REDIS.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/GPGameUinManager.php';

$params = isset($argv[1])&&is_numeric($argv[1]) ? $argv[1] : '';
if (!$params) { //这个东东只是为了防止别人直接访问这个链接，无其他意思，传个非0参数进来执行即可
    die('--k--');
}

$redisObj = new myRedis();
$redisObj->use_redis('write');
$redisObj->redis->setOption(Redis::OPT_READ_TIMEOUT, -1);

while(1) {
    $data = $redisObj->redis->lpop('LL_SUMMER_HOLIDAYS_TASK', 0);
    $taskJson = $data[1];
    handlerTask($taskJson);
}

function handlerTask($task) {
    $arr = json_decode($task, true);
    $uid = $arr['uid'];
    $systemType = $arr['systemType'];
    $addDate = date('Y-m-d H:i:s', $arr['addTime']);
    $startTime = strtotime(getStartTime());
    $endTime = strtotime(getEndTime());
    //1.0 参与话题
    if ($systemType == 2) { //66的才有每日话题
        $obj = new Db();
        $obj->use_db('lldaliytopic');
        $sql = "SELECT add_time FROM ll_reply_list WHERE uid = '{$uid}' AND add_time >= '{$startTime}' AND add_time <= '{$endTime}' ORDER BY id DESC";
        $data = $obj->query($sql);
        if ($data) {
            foreach($data as $k => $v) {
                finishUniqTask($uid, $systemType, 1, date('Ymd', $v['add_time'])); 
            }
        }
        unset($obj);
    }

    //2.0 没玩过的游戏
    if ($systemTypte == 1) { //果盘才有每日新游这个任务
        $gameUinObj = new GPGameUinManager();
        $gameUin = $gameUinObj->getGameUinByUid($uid); 
        if ($gameUin) {
            foreach($data as $k => $v) {
                if (isActivityTime($v['addtime']) !== 0) { //不在活动时间内创建的
                    continue;
                }
                finishUniqTask($uid, $systemType, 2, date('Ymd', $v['addtime']));
            }
        }
        unset($gameUinObj);
    }

    //3.0 实消6元, 实消66元
    $obj = new Db();
    if ($systemType == 2) {
        $obj->use_db('llpay');
        $sql = "SELECT sum(discount_money) AS total_money, FROM_UNIXTIME(add_time, '%Y%m%d') AS pay_date FROM pay_dev_log WHERE uin = '{$uid}' AND status = 1 AND add_time >= '{$startTime}' AND add_time <= '{$endTime}' GROUP BY pay_date";
        $data = $obj->query($sql);
        if ($data) {
            foreach($data as $k => $v) {
                if ($v['total_money'] < 6) {
                    continue;
                }
                finishUniqTask($uid, $systemType, 4, $v['pay_date']); 
                if ($v['total_money'] >= 60) {
                    finishUniqTask($uid, $systemType, 5, $v['pay_date']); 
                }
            }
        }
    }  else {
        $obj->use_db('gppay');
        $sql = "SELECT sum(money) AS total_money, FROM_UNIXTIME(add_time, '%Y%m%d') AS pay_date FROM pay_dev_log WHERE uin = '{$uid}' AND status = 1 AND add_time >= '{$startTime}' AND add_time <= '{$endTime}' GROUP BY pay_date";
        $data = $obj->query($sql);
        if ($data) {
            foreach($data as $k => $v) {
                if ($v['total_money'] < 6) {
                    continue;
                }
                finishUniqTask($uid, $systemType, 4, $v['pay_date']); 
                if ($v['total_money'] >= 60) {
                    finishUniqTask($uid, $systemType, 5, $v['pay_date']); 
                }
            }
        }
    }
     
}

function finishUniqTask($uid, $systemType, $taskId, $date) { //除分享这种可以重复完成的任务，其他任务的完成
    $tasks = getTaskInfo();
    $taskInfo = isset($tasks[$taskId]) ? $tasks[$taskId] : '';
    if (!$taskInfo) {
        addLog("task id:{$taskId} can not found");
        return 1;
    }   
    if ($taskInfo['systemType']>0 && $taskInfo['systemType']!=$systemType) {
        addLog("task id:{$taskId} system type!=finish system type {$taskInfo['systemType']}!={$systemType}");
        return 2;
    }
    $obj = new Db();
    $obj->use_db('read'); 
    $sql = "SELECT id FROM ll_summer_task_uniq WHERE uid = '{$uid}' AND system_type = '{$systemType}' AND date = '{$date}' AND task_id = '{$taskId}'";
    $data = $obj->query($sql);
    if ($data) {
        return 0; //已经完成，无需处理
    }

    $obj->use_db('write');
    $obj->query('start transaction');
    $insertArr = array(
        'uid' => $uid,
        'system_type' => $systemType,
        'date' => $date,
        'task_id' => $taskId,
        'add_time' => time(),
        'num' => $taskInfo['num'],
    );
    $obj->insert('ll_summer_task_uniq', $insertArr);
    if ($obj->db->affected_rows <= 0) {
        $obj->query('rollback');    
        addLog("insert db failed!", $insertArr);
        return 3;
    }
    $sql = "UPDATE ll_summer_user_info SET total_num = total_num + '{$taskInfo['num']}', num = num + '{$taskInfo['num']}' WHERE uid = '{$uid}' AND system_type = '{$systemType}'";
    $obj->query($sql);
    if ($obj->db->affected_rows <= 0) {
        $obj->query('rollback');
        addLog("update db failed!", $sql);
        return 4;
    }
    if (!$obj->query('commit')) {
        addLog($uid, $systemType, $taskId, $date, "commit failed");
        return 5;
    }
    return 0
}

