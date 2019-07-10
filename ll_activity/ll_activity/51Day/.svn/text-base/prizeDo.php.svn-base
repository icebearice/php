<?php
/**
 * User: ASUS
 * Date: 2019/4/2
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


$result = array(
    'code' => -1,
    'msg' => '',
    'data' => array(),
);

//是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    $result['msg'] = $time['msg'];
    echo json_encode($result);
    exit;
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

$obj = new Db();
$obj->use_db('read');

// 校验抽奖次数，并减一（放在后面的事务中）
$sql = 'SELECT total_num, last_free_times FROM ll_51_prize_user_total_num WHERE uin='. $uin;
$userNum = $obj->query($sql);
if (!isset($userNum[0]['total_num']) || $userNum[0]['total_num'] < 1) {
    $result['msg'] = '您已用完抽奖次数！';
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
} else {
    foreach ($prizeArr as $k => $v) {
        if ($v['v2_probability'] != 0) {
            $lotteryInfo[$k]['id'] = $v['id'];
            $lotteryInfo[$k]['probability'] = $v['v2_probability'] * 100;
        }
    }
    $levelField = 'v2_total_num';
}
$luckyID = getLotteryRes($lotteryInfo); // 获得奖品id


// 根据奖品id，得到库存数量，看是否要转到无限量id
$numSql = "SELECT $levelField, activity_date FROM ll_51_prize_daily_num WHERE prize_id=" . $luckyID;
$numInfo = $obj->query($numSql);
if (count($numInfo) == 0) {
    echo json_encode(array('msg'=>"{$uin}查询奖品id{$luckyID}库存信息为空错误"));
    logLn("{$uin}查询奖品id{$luckyID}库存信息为空错误");
    exit;
}
$today = date('Y-m-d', time());
$isKucunDiffDay = $numInfo[0]['activity_date'] != $today ? true : false;

if (!$isKucunDiffDay && $numInfo[0][$levelField] <= 0) {
    // 转到无限量奖品
    if ($vipLevel >= 3) {
        $luckyID = 1;
    } else {
        $luckyID = 4;
    }
}

// 得到未使用的京东券
$code = '';
if ($luckyID == 5) {
    $sqlCode = "SELECT code FROM ll_51_prize_user_jd WHERE status=0 LIMIT 1";
    $codeData = $obj->query($sqlCode);
    if (count($codeData) == 0) {
        echo json_encode(array('msg'=>"{$uin}获取京东券失败"));
        logLn("{$uin}获取京东券失败");
        exit;
    }
    $code = $codeData[0]['code'];
}


$obj->use_db('write');
$obj->query('start transaction');

// 用户抽奖次数扣除；优先扣除赠送的次数:若 last_free_times 大于0，就减一
if ($userNum[0]['last_free_times'] > 0) {
    $sqlUserNum = "UPDATE ll_51_prize_user_total_num SET total_num=total_num-1,last_free_times=last_free_times-1 WHERE uin={$uin}";
} else {
    $sqlUserNum = "UPDATE ll_51_prize_user_total_num SET total_num=total_num-1 WHERE uin={$uin}";
}
$obj->query($sqlUserNum);
$resUserNum = $obj->db->affected_rows;
if (!$resUserNum) {
    $obj->query('rollback');
    echo json_encode(array('msg'=>"uin:{$uin} 用户抽奖次数减少失败，事务回滚"));
    logLn("uin:{$uin} 用户抽奖次数减少失败，事务回滚");
    exit;
}

// 更新奖品库存
if ($isKucunDiffDay) {
    // 请求日期与库存对应日期不一致，更新日期和v2 v3的每日库存总数（对应库存的总数-1）
    if ($vipLevel >= 3) {
        $v3_total_num = $prizeArr[$luckyID-1]['v3_daily_num'] - 1;
        $v2_total_num = $prizeArr[$luckyID-1]['v2_daily_num'];
    } else {
        $v3_total_num = $prizeArr[$luckyID-1]['v3_daily_num'];
        $v2_total_num = $prizeArr[$luckyID-1]['v2_daily_num'] - 1;
    }
    $sqlKucun = "UPDATE ll_51_prize_daily_num SET v3_total_num={$v3_total_num}, v2_total_num={$v2_total_num}, activity_date='{$today}' WHERE prize_id={$luckyID}";
} else {
    // 日期一致，减少相应vip等级的奖品库存
    $sqlKucun = "UPDATE ll_51_prize_daily_num SET {$levelField}={$levelField}-1 WHERE prize_id={$luckyID}";

}
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
    'prize_date' => time(),
    'jd_code' => $code,
);
$obj->insert('ll_51_prize_user', $insertArr);
$resInsUser = $obj->db->affected_rows;
if (!$resInsUser) {
    $obj->query('rollback');
    echo json_encode(array('msg'=>"uin:{$uin} 中奖用户表写入失败，事务回滚"));
    logLn("uin:{$uin} 中奖用户表写入失败，事务回滚");
    exit;
}

if (!empty($code)) {
    // 修改code的status为已用
    $sqlCode = "UPDATE ll_51_prize_user_jd SET status=1 WHERE code='{$code}'";
    $obj->query($sqlCode);
    $resCode = $obj->db->affected_rows;
    if (!$resCode) {
        $obj->query('rollback');
        echo json_encode(array('msg'=>"uin: {$uin} 京东码状态修改失败，事务回滚"));
        logLn("uin: {$uin} 京东码状态修改失败，事务回滚");
        exit;
    }
}

// 下发奖励操作
// 通过luckyID取得后台voucher_id
if ($vipLevel >= 3) {
    $voucherId = $prizeArr[$luckyID-1]['v3_voucher_id'];
} else {
    $voucherId = $prizeArr[$luckyID-1]['v2_voucher_id'];
}
// 下发代金券
if ($voucherId != 0) {
    $voucherObj = new LLVoucherServer();
    $pushRes = $voucherObj->sendVoucher($uin, 'test-flamingo-login-key-abc', 'adc', 136, 102, $voucherId, 0);
    if (!$pushRes) {
        $obj->query('rollback');
        echo json_encode(array('msg'=>"uin: {$uin}, luckyID : $luckyID push DaiJingQuan error"));
        logLn("uin: {$uin}, luckyID : $luckyID push DaiJingQuan error");
        exit;
    }
}
// 下发成长值
if ($luckyID == 4) {
    $grouthObj = new LLGrouthServer();
    $pushRes = $grouthObj->addGrouthValueV2($uin, 30, '五一节活动奖励');
    if (!$pushRes) {
        $obj->query('rollback');
        echo json_encode(array('msg'=>"uin: {$uin},luckyID : $luckyID push ChengZhangZhi error"));
        logLn("uin: {$uin},luckyID : $luckyID push ChengZhangZhi error");
        exit;
    }
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
$result['data']['prizeName'] = $prizeArr[$luckyID-1]['name'];
$result['data']['image'] = $prizeArr[$luckyID-1]['image'];
echo json_encode($result);

