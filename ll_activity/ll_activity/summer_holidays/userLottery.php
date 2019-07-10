<?php
/*
 * 2019暑期活动，用户抽奖接口
 *
 */
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/commonFunc.php';
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/VIPInfo.php';
require_once SYSDIR_UTILS . '/GPVipInfo.php';
require_once SYSDIR_UTILS . '/voucherServer.class.php';
require_once SYSDIR_UTILS . '/GPVoucherServer.class.php';
require_once SYSDIR_UTILS . '/grouthServer.class.php';
require_once SYSDIR_UTILS . '/GPGrouthServer.class.php';
require_once SYSDIR_UTILS . '/LLIconManager.class.php';
require_once SYSDIR_UTILS . '/GPIconManager.class.php';

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

//3.0 当天是否已经抽奖
$obj = new Db();
$obj->use_db('read');
$date = date('Ymd');
$sql = "SELECT id FROM ll_summer_lottery WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND date = '{$date}'";
$data = $obj->query($sql);
if ($data) {
    $response['code'] = 6001;
    $response['msg'] = '您今天已经抽奖，请明日再来哟~'; 
    echo json_encode($response);
    exit();
}

//4.0 根据用户vip等级开始计算中奖的奖品
if ($systemType==1) {
    $vipObj = new GPVIPInfo();
} else {
    $vipObj = new VIPInfo();
}
$vipLevel = $vipObj->getVipLevel($uin);
$paramsExt = getLotteryParamsExt($vipLevel);
$sql = "SELECT id, name, icon, ptype, ext_num_{$paramsExt}, system_type, num_{$paramsExt}, probability_{$paramsExt} AS probability, kid FROM ll_summer_gift WHERE system_type = 0 AND system_type = '{$systemType}' AND date = '{$date}' ORDER BY kid ASC";
$info = $obj->query($sql);
if (!$info) {
    $response['code'] = 6002;
    $response['msg'] = '抽奖失败，请重试'; 
    echo json_encode($response);
    exit();
}
$giftInfo = getLotteryRes($info);
$lid = $giftInfo['id'];
if ($giftInfo["num_{$paramsExt}"] <= 0) {
    $lid = getDefaultGift($systemType, $vipLevel);
    foreach($info as $k => $v) {
        if ($v['id'] == $lid) {
            $giftInfo = $v;
            break;
        }
    }
}
$kid = $giftInfo['kid']; 

//5.0 开始到账
$obj->use_db('write');
$obj->query('start transaction');
//5.1 中奖纪录
$lotteryLogArr = array(
    'uid' => $uin,
    'system_type' => $systemType,
    'date' => $date,
    'lid' => $gid,
    'add_time' => date('Y-m-d H:i:s'),
);
$obj->insert('ll_summer_lottery', $lotteryLogArr);
if ($obj->db->affected_rows <= 0) {
    $obj->query('rollback');
    $response['code'] = 6003;
    $response['msg'] = '抽奖失败，请重试'; 
    echo json_encode($response);
    exit();
}
//5.2 扣奖品余量
$sql = "UPDATE ll_summer_gift SET num_{$paramsExt} = num_{$paramsExt}-1 WHERE id = '{$lid}' AND date = '{$date}' AND num_{$paramsExt} > 0";
$obj->query($sql);
if ($obj->db->affected_rows <= 0) {
    $obj->query('rollback');
    $response['code'] = 6004;
    $response['msg'] = '抽奖失败，请重试'; 
    echo json_encode($response);
    exit();
}
//5.3 纪录下我的奖品
$myGiftArr = array(
    'uid' => $uin,
    'system_type' => $systemType,
    'lid' => $gid,
    'get_type' => 1,
    'get_time' => date('Y-m-d H:i:s'),
    'ptype' => $giftInfo['ptype'],
    'params_ext' => $paramsExt,
);
$obj->insert('ll_summer_my_gift', $myGiftArr);
if ($obj->db->affected_rows <= 0) {
    $obj->query('rollback');
    $response['code'] = 6005;
    $response['msg'] = '抽奖失败，请重试'; 
    echo json_encode($response);
    exit();
}
if (!$obj->query('commit')) {
    $response['code'] = 6006;
    $response['msg'] = '抽奖失败，请重试'; 
    echo json_encode($response);
    exit();
}
//5.4 真正发放奖励
switch($giftInfo['ptype']) {
    case 1:
        $res = true;
        break;
    case 2: //代金券
        if ($systemType==2) {
            $voucherObj = new LLVoucherServer();
        } else {
            $voucherObj = new GPVoucherServer();
        }
        $res = $voucherObj->sendVoucher($uin, $_REQUEST['login_Key'], $_REQUEST['uuid'], $_REQUEST['productID']), $_REQUEST['platformType'], $giftInfo["ext_num_{$paramsExt}"]);
        $doStatus = $res ? 1 : 2; 
        $sql = "UPDATE ll_summer_lottery SET do_status = '{$doStatus}' WHERE uid = '{$uin}' AND system_type = '{$systemType}' AND date = '{$date}'";
        $obj->query($sql);
        if ($obj->db->affected_rows <= 0) {
            addLog($giftInfo, $uin, "do status failed");            
        }
        break;
    case 3: //成长值
        if ($systemType==2) {
            $groupObj = new LLGrouthServer();
        } else {
            $groupObj = new GPGrouthServer();
        }
        $res = $groupObj->addGrouthValueV2($uid, $giftInfo["ext_num_{$paramsExt}"]);
        if (!$res) {
            addLog($giftInfo, $uin, "do status failed");
        }
        break;
    case 4: //平台币
        if ($systemType==2) {
            $iconObj = new LLIconManager();
        } else {
            $iconObj = new GPIconManager();
        }
        $res = $iconObj->sendPlatformCoin($uin, $giftInfo["ext_num_{$paramsExt}"], '66活动奖励', 21);
        if (!$res) {
            addLog($giftInfo, $uin, "do status failed")
        }
        break;
}
//5.5 更新发放状态
$do_status = $res ? 1 : 2;
$sql = "UPDATE ll_summer_lottery SET do_status = 1 WHERE uid = '{$uin}', AND system_type = '{$systemType}' AND date = '{$date}'";
$obj->query($sql);
if ($obj->db->affected_rows <= 0) {
    addLog($giftInfo, $uin, "update status falied");
}

function getLotteryParamsExt($vipLevel) {
    if ($vipLevel <= 0) {
        return 'v0';
    }  
    if (0 < $vipLevel < 3) {
        return 'v1';
    }
    if ($vipLevel >= 3) {
        return 'v3';
    }
}

function getDefaultGift($systemType, $vipLevel) {
    if ($systemType == 1) {

    } 
    if ($systemType == 2) {
        if ($vipLevel <= 2) {
            return 8; 
        } 
        return 2;
    }
}
