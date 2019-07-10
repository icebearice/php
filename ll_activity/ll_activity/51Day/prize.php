<?php
/**
 * User: ASUS
 * Date: 2019/4/2
 * Time: 14:56
 *
 * 51天天抽页面奖品数据
 *
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . "/userInfoServer.class.php";

$result = array(
    'code' => -1,
    'msg' => '',
    'data' => array(),
);

// 是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    $result['msg'] = $time['msg'];
    echo json_encode($result);
    exit;
}

$obj = new Db();
$obj->use_db('read');
// 奖品列表
foreach ($prizeArr as $k => $v) {
    $result['data']['prizeList'][$k]['id'] = $v['id'];
    $result['data']['prizeList'][$k]['name'] = $v['name'];
    $result['data']['prizeList'][$k]['image'] = $v['image'];
    $result['data']['prizeList'][$k]['position'] = $v['position'];
}
array_multisort(getColumn2Arr($result['data']['prizeList'], 'position'), SORT_ASC, $result['data']['prizeList']);

// 中奖名单获取逻辑
$userHandler = new LLUserInfoServer();
$sqlPrize = "SELECT uin, prize_id, prize_date FROM ll_51_prize_user WHERE prize_id!=4 ORDER BY prize_date desc limit 30";
$prizeData = $obj->query($sqlPrize);
if (count($prizeData) < 1) {
    $result['data']['prizeUser'] = array();
} else {
    $prizeInfo = array();
    foreach ($prizeData as $k => $v) {
        $prizeInfo[$k]['prize_date'] = $v['prize_date'];
        // 根据	prize_id 获取 prizeName
        $prizeId = $v['prize_id'];
        $prizeInfo[$k]['prizeName'] = $prizeArr[$prizeId-1]['name'];
        // 根据uin获取name
        $userInfo = $userHandler->getUserInfoByUin($v['uin']);
        $userInfo = json_decode(json_encode($userInfo), true);
        $name = isset($userInfo['base_data']['unickname']) ? $userInfo['base_data']['unickname'] : $userInfo['base_data']['uname'] ;
        // name显示处理逻辑
        if ( mb_strlen($name) == 1) {
            $prizeInfo[$k]['name'] = $name . '**' . $name;
        } else {
            $prizeInfo[$k]['name'] = mb_substr($name, 0, 1, 'utf-8') . '**' . mb_substr($name, -1, 1, 'utf-8');
        }
    }
    $result['data']['prizeUser'] = $prizeInfo;
}


// 是否登录
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;

$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    // 没登录，只看列表页面和中奖用户
    $result['code'] = 1;
    echo json_encode($result);exit;
}

//已登录
// 抽奖剩余次数获取和增加逻辑
$today = date('Y-m-d', time());
$sql = 'SELECT total_num, last_free_date, last_free_times FROM ll_51_prize_user_total_num WHERE uin='. $uin;
$data = $obj->query($sql);

if (empty($data)) {
    $insertData = array();
    $insertData['uin'] = $uin;
    $insertData['total_num'] = 1;
    $insertData['last_free_date'] = $today;
    $insertData['last_free_times'] = 1; // last_free_date日期下有1次免费机会未使用
    $obj->use_db('write');
    $obj->insert('ll_51_prize_user_total_num', $insertData);
    $status = $obj->db->affected_rows;
    if (!$status) {
        $result['msg'] = '执行错误，请稍后重试';
        $result['code'] = '5102';
        echo json_encode($result);
        logLn("uin: {$uin} insert ll_51_prize_user_total_num data error");
        exit();
    }
    $result['data']['totalNum'] = 1;

} else {
    // 有记录，对比表中 上次免费赠送次数的日期和当前日期 是否一样，不一样则说明需要赠送免费次数，并修改免费赠送日期和次数；并以该日期为标识，若不同，需要清除昨日免费次数
    if ($data[0]['last_free_date'] != $today) {
        // 日期变化
        $obj->use_db('write');
        // 新的一天，修改日期和给予免费次数
        $updaInfo['total_num'] = $data[0]['total_num'] + 1;
        $updaInfo['last_free_date'] = $today;
        $updaInfo['last_free_times'] = $data[0]['last_free_times'] + 1;

        //判断昨日的免费次数有没有使用（抽奖时扣除逻辑：先清除每日赠送次数，然后清除消费永久）
        if ($data[0]['last_free_times'] > 0) {
            // 昨日免费次数未使用，需要清除，并将标识次数修改
            $updaInfo['total_num'] = $updaInfo['total_num'] - 1;
            $updaInfo['last_free_times'] = $updaInfo['last_free_times'] - 1;
        }
        $obj->update('ll_51_prize_user_total_num', $updaInfo, ['uin'=>$uin]);
        $status = $obj->db->affected_rows;
        if (!$status) {
            $result['msg'] = '执行错误，请稍后重试';
            $result['code'] = '5103';
            echo json_encode($result);
            logLn("uin: {$uin} update ll_51_prize_user_total_num data error");
            exit();
        }
        $result['data']['totalNum'] = $updaInfo['total_num'];
    } else {
        // 日期没变化
        $result['data']['totalNum'] = $data[0]['total_num'];
    }
}

// 获取uname
$userInfo = $userHandler->getUserInfoByUin($uin);
$userInfo = json_decode(json_encode($userInfo), true);
$result['data']['name'] = isset($userInfo['base_data']['unickname']) ? $userInfo['base_data']['unickname'] : $userInfo['base_data']['uname'] ;

// 执行正确
$result['code'] = 1;
echo json_encode($result);