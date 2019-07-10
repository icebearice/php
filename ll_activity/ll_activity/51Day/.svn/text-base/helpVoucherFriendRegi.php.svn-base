<?php
/**
 * User: ASUS
 * Date: 2019/4/9
 * Time: 16:42
 *
 * 定时脚本，每隔3分钟刷 AccountTransactions 库中的 ll_invite_account_log 表
 *
 */
//  */3 * * * * /services/ll_activity/ll_activity/51Day/helpVoucherFriendRegi

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once SYSDIR_UTILS . '/DB.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once SYSDIR_UTILS . '/voucherServer.class.php';
require_once dirname(__FILE__) . '/common/func.php';

logLnInvite('----------start------------');

$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    logLnInvite("未开始");
    logLnInvite('-----------end-----------');
    exit;
}

// 得到三分钟内受邀注册的用户
$obj = new Db();
$obj->use_db('accountTrans');
$threeMin = time() - 180;
$sql = "SELECT share_uin, add_time, invite_uin FROM ll_invite_account_log WHERE add_time >= $threeMin";
$autoData = $obj->query($sql);

if (empty($autoData)) {
    logLnInvite("no invited user to register in 3 minute");
    logLnInvite('-----------end-----------');
    exit;
}


// 得到参加助力活动 且 还有剩余时间的用户
$obj->use_db('read');
$threeHou = time() - 10800;
// 特殊时间: 若时间在凌晨3点以内，则只扫描 select_date 到0点的.(如凌晨1点该脚本自动执行，则只扫描0点后参加助力的活动用户)
if (date('H', time()) < 3) {
    $threeHou = strtotime(date('Y-m-d', time()));
}
$sqlSele = "SELECT id,uin, select_date, status, friend_help_num, voucher_id FROM ll_51_help_voucher_select WHERE select_date > $threeHou and status=0";
$seleData = $obj->query($sqlSele);
if (empty($seleData)) {
    logLnInvite("no user join helping activity in 3 hours ");
    logLnInvite('-----------end-----------');
    exit;
}

// 得到成功让新用户注册的 参加助力活动的用户
$regiData = array();
foreach ($autoData as $k => $v) {
    foreach ($seleData as $kk => $vv) {
        if ($vv['uin'] == $v['share_uin']) {
            $regiData[$k]['id'] = $vv['id'];
            $regiData[$k]['voucher_id'] = $vv['voucher_id'];
            $regiData[$k]['uin'] = $v['share_uin'];
            $regiData[$k]['add_time'] = $v['add_time'];
            $regiData[$k]['invite_uin'] = $v['invite_uin'];
        }
    }
}

if (empty($regiData)) {
    logLnInvite('have no suitable data');
    logLnInvite('-----------end-----------');
    exit;
}

$today = date('Y-m-d', time());
$voucherObj = new LLVoucherServer();

$flag = true;
foreach ($regiData as $k => $v) {
    // 查询，防止有重复记录
    $sqlCheck = "SELECT id FROM ll_51_help_voucher_log WHERE type=2 and help_info={$v['invite_uin']}";
    $res = $obj->query($sqlCheck);
    if (count($res) >= 1) {
        logLnInvite('userid:' . $v['uin'] . ' success to invite user to register id:' . $v['invite_uin'] . ' but this id are repeated in record');
        continue;
    } else {
        // 符合条件
        // 查询 select 表，计算 friend_help_num 看是否修改 status
        $sqlNum = "SELECT friend_help_num FROM ll_51_help_voucher_select WHERE id={$v['id']}";
        $numInfo = $obj->query($sqlNum);
        $helpNum = $helpArr[$v['voucher_id']-1]['help_times'];
        $friend_help_num = $numInfo[0]['friend_help_num'] + 5;

        $obj->use_db('write');
        $obj->query('start transaction');
        if ( $friend_help_num >= $helpNum ) {
            // 修改 status 和 friend_help_num
            $sqlUpda = "UPDATE ll_51_help_voucher_select SET status=2, friend_help_num=$friend_help_num WHERE id={$v['id']} ";
            $obj->query($sqlUpda);
            $updaRows = $obj->db->affected_rows;
            if (!$updaRows) {
                $obj->query('rollback');
                logLnInvite("uin:{$uin} UPDATE ll_51_help_voucher_select error");continue;
            }

            // 下发代金券
            $voucherId = $helpArr[$v['voucher_id']-1]['voucher_id'];
            $pushRes = $voucherObj->sendVoucher($v['uin'], 'test-flamingo-login-key-abc', 'adc', 136, 102, $voucherId, 0);
            if (!$pushRes) {
                $obj->query('rollback');
                logLnInvite("uin:{$uin} sendVoucher error");continue;
            }

        } else {
            // 修改friend_help_num
            $sqlUpda = "UPDATE ll_51_help_voucher_select SET friend_help_num=$friend_help_num WHERE id={$v['id']}";
            $obj->query($sqlUpda);
            $updaRows = $obj->db->affected_rows;
            if (!$updaRows) {
                $obj->query('rollback');
                logLnInvite("uin:{$uin} UPDATE ll_51_help_voucher_select error");continue;
            }
        }
        // 写入 log 表
        $obj->use_db('write');
        $sqlInse = "INSERT INTO ll_51_help_voucher_log VALUES('', {$v['id']}, 2, {$v['invite_uin']}, '{$today}')";
        $obj->query($sqlInse);
        $inseRows = $obj->db->affected_rows;
        if (!$inseRows) {
            $obj->query('rollback');
            logLnInvite("uin:{$uin} INSERT ll_51_help_voucher_log error");continue;
        }
        $obj->query('commit');

        logLnInvite('userid:' . $v['uin'] . ' success to invite user to register id:' . $v['invite_uin']);
        $flag = false;
    }
}

if ($flag) {
    logLnInvite('have suitable data but check data all are repeated to throw away');
}
logLnInvite('-----------end-----------');



function logLnInvite() {
    $stack = debug_backtrace();
    $args = func_get_args();
    $data = "";
    $file_name = sprintf("/tmp/51Day_invite_%s_Logger.log",date("Y-m-d"));
    if (count($stack) > 1) {
        $data = sprintf("%s %s %s %d ", date("Y-m-d H:i:s"), $stack[1]["file"], $stack[1]["function"], $stack[0]["line"]);
    }else {
        $data = sprintf("%s %s %s %d ", date("Y-m-d H:i:s"), $stack[0]["file"], $stack[0]["function"], $stack[0]["line"]);
    }
    @file_put_contents($file_name, $data. json_encode($args, JSON_UNESCAPED_UNICODE). "\r\n", FILE_APPEND);
}
