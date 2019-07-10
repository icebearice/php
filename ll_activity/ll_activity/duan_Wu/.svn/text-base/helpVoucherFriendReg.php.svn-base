<?php
/**
 * User: haian.jin
 * Date: 2019/06/01
 * Time: 16:42
 *
 * 定时脚本，每隔3分钟刷 AccountTransactions 库中的 ll_invite_account_log 表
 *
 */
//  */3 * * * * /services/ll_activity/ll_activity/duanWu/helpVoucherFriendRegi

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

$now=date("Y-m-d");
$obj->use_db('read');

// 得到成功让新用户注册的 参加助力活动的用户
$regiData = array();
foreach ($autoData as $k => $v) {
        $uin=$v['share_uin'];
	$sqlSele="select * from ll_duanwu_help_voucher_select where uin=$uin and select_date='{$now}'";
        logLnInvite($sqlSele);
	$se=$obj->query($sqlSele);
        if(empty($se)){
            $obj->use_db('write');
	    $obj->query('start transaction');
            $sqlInsert="insert into ll_duanwu_help_voucher_select (uin,select_date,count_of_nuo_mi,friend_help_num )values($uin,'{$now}',5,5)";
           
	    logLnInvite($sqlInsert);
            $obj->query($sqlInsert);
            $updaRows = $obj->db->affected_rows;
            if (!$updaRows) {
                $obj->query('rollback');
                logLnInvite("uin:{$uin} insert ll_duanwu_help_voucher_log error");continue;
            }
            $obj->query('commit');
        }else{
            $regiData[$k]['uin'] = $v['share_uin'];
            $regiData[$k]['add_time'] = $now;
        }
    }


if (empty($regiData)) {
    logLnInvite('have no suitable data');
    logLnInvite('-----------end-----------');
    exit;
}

$today = date('Y-m-d', time());
foreach ($regiData as $k => $v) {
    // 查询 select 表，增加糯米数
    $obj->use_db('write');
    $obj->query('start transaction');
    $uin=$v['uin'];
    $sqlUpda = "UPDATE ll_duanwu_help_voucher_select SET  count_of_nuo_mi=count_of_nuo_mi+5,friend_help_num=friend_help_num+5 WHERE uin=$uin and select_date ='{$now}' ";
     logLnInvite($sqlUpda);
    $obj->query($sqlUpda);
    $updaRows = $obj->db->affected_rows;
    if (!$updaRows) {
        $obj->query('rollback');
        logLnInvite("uin:{$uin} UPDATE ll_duanwu_help_voucher_log error");continue;
    }
    $obj->query('commit');
}

logLnInvite('-----------end-----------');



function logLnInvite() {
    $stack = debug_backtrace();
    $args = func_get_args();
    $data = "";
    $file_name = sprintf("/tmp/duanWu_invite_%s_Logger.log",date("Y-m-d"));
    if (count($stack) > 1) {
        $data = sprintf("%s %s %s %d ", date("Y-m-d H:i:s"), $stack[1]["file"], $stack[1]["function"], $stack[0]["line"]);
    }else {
        $data = sprintf("%s %s %s %d ", date("Y-m-d H:i:s"), $stack[0]["file"], $stack[0]["function"], $stack[0]["line"]);
    }
    @file_put_contents($file_name, $data. json_encode($args, JSON_UNESCAPED_UNICODE). "\r\n", FILE_APPEND);
}
