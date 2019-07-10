<?php
/*
 * 夺宝结算
 * 2019-06-27
 *
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/commonFunc.php';
//require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/REDIS.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/VIPInfo.php';
require_once SYSDIR_UTILS . '/GPVipInfo.php';
//require_once SYSDIR_UTILS . '/voucherServer.class.php';
//require_once SYSDIR_UTILS . '/GPVoucherServer.class.php';
//require_once SYSDIR_UTILS . '/grouthServer.class.php';
//require_once SYSDIR_UTILS . '/GPGrouthServer.class.php';
//require_once SYSDIR_UTILS . '/LLIconManager.class.php';
//require_once SYSDIR_UTILS . '/GPIconManager.class.php';

function __autoload($classname) {
    $classpath = SYSDIR_UTILS . "/" . $classname . '.class.php';
    if(file_exists($classpath)){
        require_once($classpath);
    }   else {

        throw new Exception('class file' . $classpath . 'not found');
    }
}

// 夺宝期数
$pid = isset($argv[1])&&is_numeric($argv[1]) ? $argv[1] : '';
if (!$pid) {
    die('ii--ii');
}
// 系统类型
$systemType = isset($argv[2])&&is_numeric($argv[2]) ? $argv[2] : '';
if (!$systemType) {
    die('jj--jj');
}

$systemTypeArr = array(
    1 => '果盘',
    2 => '66',
);
$obj = new Db();
$obj->use_db('read');

$sql = "SELECT uid, system_type, periods_id, prize_id, status, do_status FROM ll_summer_fight_gem WHERE periods_id = '{$pid}' AND 'system_type' = '{$systeType}'";
$data = $obj->query($sql);
$joinArr = array();
if (!$data) {
    echo "{$systemTypeArr[$systemType]}本期未有用户参与夺宝\n";
} else {
    foreach($data as $k => $v) {
        $joinArr[$v['prize_id']][] = $v;
    } 
}

$obj->use_db('write');
$fightInfo = getFightInfo($systemType);
if ($joinArr) {
    foreach($fightInfo as $k => $v) {
        $hitArr = array();
        $thisArr = isset($joinArr[$v['id']]) ? $joinArr[$v['id']] : array();
        $hitNum = $v['hitNum'];
        if (!$thisArr) {
            echo "{$systemTypeArr[$systemType]}本期奖品id{$v['id']}未有用户参与夺宝\n";
            continue;
        } else {
            $hitArr = fightGem($thisArr, $hitNum); //中奖的用户
        }
        $count = count($hitArr);
        echo "{$systemTypeArr[$systemType]}本期奖品id{$v['id']} 预计中奖人数{$hitNum} 实际中奖人数{$count}\n";
        //更新处理状态 
        $sql = "UPDATE ll_summer_fight_gem SET status = 2 WHERE uid IN (".explode(',', $hitArr).") AND system_type = '{$systeType}' AND periods_id = '{$pid}' AND prize_id = '{$v['id']}'";
        $obj->query($sql);
        echo $sql . "\n";
        $sql = "UPDATE ll_summer_fight_gem SET status = 1 WHERE uid NOT IN (".explode(',', $hitArr).") AND system_type = '{$systeType}' AND periods_id = '{$pid}' AND prize_id = '{$v['id']}'";
        $obj->query($sql);
        echo $sql . "\n";

        //发放奖励
        foreach ($hitArr as $uk => $uv) {
            switch($v['type']) {
            case 1:
                $res = true;
                break;

            case 2:
                if ($systemType==2) {
                    $voucherObj = new LLVoucherServer();
                } else {
                    $voucherObj = new GPVoucherServer();
                }
                $res = $voucherObj->sendVoucher($uv['uid'], 'test-flamingo-login-key-abc', 'shuqi-duobao-huodong', 136, 102, $v['num']);
                if ($res) {
                    $sql = "UPDATE ll_summer_fight_gem SET do_status = 1 WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND date = '{$date}'";
                    $obj->query($sql);
                    if ($obj->db->affected_rows <= 0) {
                        addLog($uv, $v, "do status failed");            
                    }
                }
                break;

            case 3:
                if ($systemType==2) {
                    $groupObj = new LLGrouthServer();
                } else {
                    $groupObj = new GPGrouthServer();
                }
                $res = $groupObj->addGrouthValueV2($uv['uid'], $v['num']);
                if (!$res) {
                    addLog($uv, $v, "do status failed");
                }
                break;

            case 4:
                if ($systemType==2) {
                    $iconObj = new LLIconManager();
                } else {
                    $iconObj = new GPIconManager();
                }
                $res = $iconObj->sendPlatformCoin($uv['uid'], $v['num'], '66活动奖励', 21);
                if (!$res) {
                    addLog($uv, $v, "do status failed")
                }
                break;
            } 
        }
    }
}


function fightGem($arr, $hitNum) {
    if (!$arr || count($arr)<=0) {
        return array();
    } 
    shuffle($arr);
    $hitArr = array();
    foreach($arr as $k => $v) {
        if (count($hitArr) >= $hitNum) {
            break;
        }
        $hitArr[] = $v;
    } 
    return $hitArr; 
}
