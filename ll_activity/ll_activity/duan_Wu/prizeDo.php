<?php
/**
 * User: haian.jin
 * Date: 2019/5/17
 * Time: 15:24
 *
 *  执行抽奖接口
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/VIPInfo.php";
require_once SYSDIR_UTILS . '/voucherServer.class.php';
require_once SYSDIR_UTILS . '/grouthServer.class.php';
require_once SYSDIR_UTILS . '/LLIconManager.class.php';

$result = array(
    'code' => -1,
    'msg' => '',
    'data' => array(),
);

@file_put_contents("/tmp/prize.log",date('Y-m-d h:m:i')." 请求参数：".var_export($_REQUEST,true)."\n",FILE_APPEND );
//是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    echo json_encode($result);
    conManagerexit;
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
    $response['code'] = ErrorCode::User_Not_Login;
    $response['msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$obj = new Db();
$obj->use_db('read');
$now=date("Y-m-d");
// 校验抽奖次数，并减一（放在后面的事务中）
$sql = "SELECT zongzi_num FROM ll_duanwu_user_prize_count WHERE  uin={$uin} AND active_date='{$now}'";
$userNum = $obj->query($sql);
if (!isset($userNum[0]['zongzi_num']) || $userNum[0]['zongzi_num'] < 1) {
    $result['msg'] = '粽子数不够';
    $result['code'] = '5104';
    echo json_encode($result);
    exit();
}

// 执行抽奖，获得奖品id
// 当前用户等级
$vipHandler = new VIPInfo();
$vip = $vipHandler->getVipLevel($uin);
$vipLevel = json_decode(json_encode($vip), true);
$lotteryInfo = array();
if ($vipLevel >= 3) {
    foreach ($prizeArr as $k => $v) {
        if ($v['v3_probability'] != 0) {
            $lotteryInfo[$k]['id'] = $v['id'];
            $lotteryInfo[$k]['probability'] = $v['v3_probability'] * 100;
        }
    }
    $levelField = 'v3_total_num';
} else if ($vipLevel<3&&$vipLevel>=1) {
    foreach ($prizeArr as $k => $v) {
        if ($v['v1_v2_probability'] != 0) {
            $lotteryInfo[$k]['id'] = $v['id'];
            $lotteryInfo[$k]['probability'] = $v['v1_v2_probability'] * 100;
        }
    }
    $levelField = 'v1_v2_total_num';
}else{
    foreach ($prizeArr as $k => $v) {
        if ($v['v0_probability'] != 0) {
            $lotteryInfo[$k]['id'] = $v['id'];
            $lotteryInfo[$k]['probability'] = $v['v0_probability'] * 100;
        }
    }
    $levelField = 'v0_total_num';
}
$luckyID = getLotteryRes($lotteryInfo); // 获得奖品id

// 根据奖品id，得到库存数量，看是否要转到无限量id
$numSql = "SELECT * FROM ll_duanwu_prize_daily_num WHERE prize_id=$luckyID and activity_date='{$now}'"  ;
$numInfo = $obj->query($numSql);
if (count($numInfo) == 0) {
    echo json_encode(array('msg'=>"{$uin}查询奖品id{$luckyID}库存信息为空错误"));
    logLn("{$uin}查询奖品id{$luckyID}库存信息为空错误");
    exit;
}

$total_num=$numInfo[0]['total_num'];
if ( $total_num==0) {
    // 转到无限量奖品
    if ($vipLevel >= 3) {
        $luckyID = 7;
    } else {
        $luckyID = 3;
    }
}




$obj->use_db('write');
$obj->query('start transaction');

//减少用户粽子数
$sqlUserNum = "UPDATE ll_duanwu_user_prize_count SET zongzi_num=zongzi_num-1 WHERE uin={$uin} and active_date='{$now}'";
$obj->query($sqlUserNum);
$resUserNum = $obj->db->affected_rows;
if (!$resUserNum) {
    $obj->query('rollback');
    echo json_encode(array('msg'=>"uin:{$uin} 用户抽奖次数减少失败，事务回滚"));
    logLn("uin:{$uin} 用户抽奖次数减少失败，事务回滚");
    exit;
}

// 更新奖品库存
$sqlKucun = "UPDATE ll_duanwu_prize_daily_num SET total_num=total_num-1  WHERE prize_id={$luckyID} and activity_date='{$now}' and total_num>=1";
$obj->query($sqlKucun);
$resKucun = $obj->db->affected_rows;
if (!$resKucun) {
    $obj->query('rollback');
    echo json_encode(array('msg'=>"uin:{$uin} 奖品库存减少失败，事务回滚 {$resKucun}"));
    logLn("uin:{$uin} 奖品库存减少失败，事务回滚 {$resKucun}");
    exit;
}

// 写入中奖的用户表
$insertArr = array(
    'uin' => $uin,
    'prize_id' => $luckyID,
    'prize_date' => $now,
    'status'=>0
);
$obj->insert('ll_duanwu_user_prize', $insertArr);
$resInsUser = $obj->db->affected_rows;
@file_put_contents("/tmp/prize.log",date('Y-m-d h:m:i')." 写入中户中奖记录：".var_export($insertArr,true)." affected:".$resInsUser."\n",FILE_APPEND );
if (!$resInsUser) {
    $obj->query('rollback');
    echo json_encode(array('msg'=>"uin:{$uin} 中奖用户表写入失败，事务回滚"));
    logLn("uin:{$uin} 中奖用户表写入失败，事务回滚");
    exit;
}
// 下发奖励操作
// 通过luckyID取得后台voucher_id
if ($vipLevel >= 3) {
    $voucherId = $prizeArr[$luckyID-1]['v3_voucher_id'];
} else if ($vipLevel<3&&$vipLevel>=1){
    $voucherId = $prizeArr[$luckyID-1]['v1_v2_voucher_id'];
}else{
    $voucherId=$prizeArr[$luckyID-1]['v0_voucher_id'];
}
// 下发代金券
if ($voucherId != 0) {
    $voucherObj = new LLVoucherServer();
    $pushRes = $voucherObj->sendVoucher($uin, 'test-flamingo-login-key-abc', 'adc', 136, 102, $voucherId, 0);
    if (!$pushRes) {
        $obj->query('rollback');
        echo json_encode(array('msg'=>"uin: {$uin}, luckyID : $voucherId push DaiJingQuan error"));
        logLn("uin: {$uin}, luckyID : $luckyID push DaiJingQuan error");
        exit;
    }
}
//检查是否抽到平台币
if ($luckyID==11){
    $iconManager=new LLIconManager();
    $flag=$iconManager->sendPlatformCoin($uin,2,"平台币活动赠币",21);
    if (!$flag){
        $obj->query('rollback');
        echo json_encode(array('msg'=>"uin: {$uin}, luckyID : $luckyID push PingtaiBi error"));
        logLn("uin: {$uin}, luckyID : $luckyID push PingtaiBi error");
        exit;
    }
}
// 下发成长值
if ($luckyID == 3) {
    $grouthObj = new LLGrouthServer();
    $pushRes = $grouthObj->addGrouthValueV2($uin, 30, '端午节活动');
    if (!$pushRes) {
        $obj->query('rollback');
        echo json_encode(array('msg'=>"uin: {$uin},luckyID : $luckyID push ChengZhangZhi error"));
        logLn("uin: {$uin},luckyID : $luckyID push ChengZhangZhi error");
        exit;
    }
}
//都发放完
//发放完记录表对应的状态改为1
$sql="UPDATE ll_duanwu_user_prize SET status=1 WHERE prize_id={$luckyID} and prize_date='{$now}' and uin=$uin";
$obj->query($sql);
$resKucun = $obj->db->affected_rows;
if (!$resKucun) {
    $obj->query('rollback');
    echo json_encode(array('msg'=>"奖品发完，但是状态未改变"));
    exit;
}
if (!$obj->query('commit')) {
    $obj->query('rollback');
    echo json_encode(array('msg'=>"uin: {$uin} 事务提交失败，事务回滚"));
    logLn("uin: {$uin} 事务提交失败，事务回滚");
    exit;
}

// 执行成功
$result['code'] = 1;
$result['msg'] = 'success';
$result['data']['id'] = $prizeArr[$luckyID-1]['id'];
$result['data']['position'] = $prizeArr[$luckyID-1]['position'];
$result['data']['type']=$prizeArr[$luckyID-1]['type'];
$result['data']['prizeName'] = $prizeArr[$luckyID-1]['name'];
$result['data']['image'] = $prizeArr[$luckyID-1]['image'];
echo json_encode($result);
