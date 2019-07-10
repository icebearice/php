<?php
/**
 * User: ASUS
 * Date: 2019/4/4
 * Time: 11:36
 *
 *  我的奖品 页面数据
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';

$result = array(
    'code' => -1,
    'msg' => '',
    'data' => array(),
);

//是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    $result['msg'] = $time['msg'];
    echo json_encode($result);exit;
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

$allData = array();
$xh = 0;
$obj = new Db();
$obj->use_db('read');

// 抽奖模块
$sqlPrize = "SELECT prize_id, prize_date, jd_code FROM ll_51_prize_user WHERE uin={$uin} ORDER BY prize_date desc";
$prizeData = $obj->query($sqlPrize);
// 充值模块
$sqlConsume = "SELECT get_job_hundred_voucher_id, get_job_hundred_voucher_date FROM ll_51_consume_log WHERE uin={$uin} and get_job_hundred_voucher_id!=0 ORDER BY get_job_hundred_voucher_date desc ";
$consData = $obj->query($sqlConsume);
// 助力模块
$sqlHelp = "SELECT voucher_id, select_date FROM ll_51_help_voucher_select WHERE uin={$uin} and status=2 ORDER BY select_date desc";
$helpData = $obj->query($sqlHelp);

if (empty($prizeData) && empty($consData) && empty($helpData) ) {
    echo json_encode(array('code'=>1, 'msg'=>'无数据'));
}


if (is_array($prizeData) && !empty($prizeData)) {
    foreach ($prizeData as $k => $v) {
        $index = $v['prize_id'] - 1;
        $allData[$xh]['id'] = $v['prize_id'];
        $allData[$xh]['prizeDate'] = date('Y-m-d H:i:s', $v['prize_date']);
        $allData[$xh]['image'] = $prizeArr[$index]['image'];
        switch ($index) {
            case 0:
                $allData[$xh]['typeName'] = '抽奖';
                $allData[$xh]['num'] =  1;
                $allData[$xh]['condition'] = '满6元使用';
                $allData[$xh]['expired'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d', ($v['prize_date'] +  $prizeArr[$index]['expired']))) + 86400 -1);
                continue;
            case 1:
                $allData[$xh]['typeName'] = '抽奖';
                $allData[$xh]['num'] =  20;
                $allData[$xh]['condition'] = '满128元使用';
                $allData[$xh]['expired'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d', ($v['prize_date'] +  $prizeArr[$index]['expired']))) + 86400 -1);
                continue;
            case 2:
                $allData[$xh]['typeName'] = '抽奖';
                $allData[$xh]['num'] =  5;
                $allData[$xh]['condition'] = '满20元使用';
                $allData[$xh]['expired'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d', ($v['prize_date'] +  $prizeArr[$index]['expired']))) + 86400 -1);
                continue;
            case 3:
                $allData[$xh]['num'] =  30;
                $allData[$xh]['condition'] = '成长值';
                continue;
            case 4:
                $allData[$xh]['num'] =  10;
                $allData[$xh]['condition'] = '京东卡';
                $allData[$xh]['jdCode'] = $v['jd_code'];
                continue;
            case 5:
                $allData[$xh]['typeName'] = '抽奖';
                $allData[$xh]['num'] =  100;
                $allData[$xh]['condition'] = '满648元使用';
                $allData[$xh]['expired'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d', ($v['prize_date'] +  $prizeArr[$index]['expired']))) + 86400 -1);
                continue;
            case 6:
            case 7:
            default:
        }
        $xh++;
    }
}



if (is_array($consData) && !empty($consData)) {
    foreach ($consData as $k => $v) {
        $index = $v['get_job_hundred_voucher_id'] - 1; // 配置文件的数组索引
        $allData[$xh]['typeName'] = '充值';
        $allData[$xh]['prizeDate'] = date('Y-m-d H:i:s', $v['get_job_hundred_voucher_date']);
        $allData[$xh]['image'] = $consumeArr[$index]['image'];
        $allData[$xh]['expired'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d', ($consumeArr[$index]['expired'] + $v['get_job_hundred_voucher_date']))) + 86400 - 1);

        switch ($index) {
            case 0:
                $allData[$xh]['num'] =  2;
                $allData[$xh]['condition'] = '满6元使用';
                continue;
            case 1:
                $allData[$xh]['num'] =  4;
                $allData[$xh]['condition'] = '满12元使用';
                continue;
            case 2:
                $allData[$xh]['num'] =  8;
                $allData[$xh]['condition'] = '满20元使用';
                continue;
            case 3:
                $allData[$xh]['num'] =  25;
                $allData[$xh]['condition'] = '满68元使用';
                continue;
            case 4:
                $allData[$xh]['num'] =  45;
                $allData[$xh]['condition'] = '满128元使用';
                continue;
            default:
        }
        $xh++;
    }
}

if (is_array($helpData) && !empty($helpData)) {
    foreach ($helpData as $k => $v) {
        $index = $v['voucher_id'] - 1;
        $allData[$xh]['typeName'] = '助力';
        $allData[$xh]['prizeDate'] = date('Y-m-d H:i:s', $v['select_date']);
        $allData[$xh]['image'] = $helpArr[$index]['image'];
        $allData[$xh]['expired'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d', ($helpArr[$index]['expired'] + $v['select_date']))) + 86400 - 1);

        switch ($index) {
            case 0:
                $allData[$xh]['num'] =  2;
                $allData[$xh]['condition'] = '满6元使用';
                continue;
            case 1:
                $allData[$xh]['num'] =  4;
                $allData[$xh]['condition'] = '满12元使用';
                continue;
            case 2:
                $allData[$xh]['num'] =  8;
                $allData[$xh]['condition'] = '满20元使用';
                continue;
            case 3:
                $allData[$xh]['num'] =  25;
                $allData[$xh]['condition'] = '满68元使用';
                continue;
            case 4:
                $allData[$xh]['num'] =  45;
                $allData[$xh]['condition'] = '满128元使用';
                continue;
            default:
        }
        $xh++;
    }
}

// 根据 prizeData 倒序
array_multisort(getColumn2Arr($allData, 'prizeDate'), SORT_DESC, $allData);


$result['code'] = 1;
$result['data'] = $allData;
echo json_encode($result);
