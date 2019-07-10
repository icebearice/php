<?php
/**

 * User: ASUS
 * Date: 2019/4/8
 * Time: 16:53
 * 神券免费得 -- 列表信息
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . '/voucherServer.class.php';
require_once SYSDIR_UTILS . '/XXRequestBase.php';

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

// 用户 未登录/选中0张券
foreach ($helpArr as $k => $v) {
    $result['data']['voucherList'][$k]['id'] = '';
    $result['data']['voucherList'][$k]['voucherId'] = $v['id'];
    $result['data']['voucherList'][$k]['name'] = $v['name'];
    $result['data']['voucherList'][$k]['image'] = $v['image'];
    $result['data']['voucherList'][$k]['help_times'] = $v['help_times'];
    if ($v['fangshua'] == 1) {
        // 开启了防刷
        $result['data']['voucherList'][$k]['buttStatus'] = 3;
    } else {
        $result['data']['voucherList'][$k]['buttStatus'] = 1; // 1表示"红色选我"； 2表示"查看"； 3表示已领完； 4 表示"灰色选我"
    }
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
    if (!empty($_GET['seleId'])) { // 前端要求标识
        echo json_encode(array('code'=>503, 'msg'=>'您还没有登录'));
        exit;
    }
    $result['code'] = 1;
    echo json_encode($result);exit;
}

// 已登录
$obj = new Db();
$obj->use_db('read');
$before = strtotime(date('Y-m-d', time()));
$after = $before + 86400;
$seleSql = "SELECT id, uin, status, voucher_id FROM ll_51_help_voucher_select WHERE uin=$uin and select_date > $before and select_date < $after "; // 每人每天最多可得2张券，次日可重新领取
$seleData = $obj->query($seleSql);

// 选中神券逻辑
// 对请求进行校验：每日最多2张券，该日没有助力券处于正在进行或失败状态, 该券库存状态处于非防刷状态，
if (isset($_GET['seleId']) && is_numeric($_GET['seleId'])) {
    $result['data'] = array();
    $seleId = $_GET['seleId'];
    if ($seleId < 1 || $seleId > 5) {
        $result['msg'] = 'get seleId error';
        $result['code'] = 5115;
        echo json_encode($result);exit;
    }
    if (count($seleData) >= 2) {
        $result['msg'] = 'choose more than 2 times today!';
        $result['code'] = 5116;
        echo json_encode($result);exit;
    }

    if ($helpArr[$seleId - 1]['fangshua'] == 1) {
        //开启防刷了，不能选择
        $result['msg'] = 'fangshuaING';
        $result['code'] = 5118;
        echo json_encode($result);exit;
    }

    if (count($seleData) == 1) {
        //今日已发起一个助力，且没完成助力，任务作废，且今日之内不能对该券继续发起助力
        if ($seleData[0]['status'] == 0) {
            $result['msg'] = 'have a helping activity!';
            $result['code'] = 5117;
            echo json_encode($result);exit;
        }
        // 如果选择了，则不能选择已经选中的代金券
        if ($seleData[0]['voucher_id'] == $seleId) {
            $result['msg'] = 'you have chosen voucher today!';
            $result['code'] = 51171;
            echo json_encode($result);exit;
        }
    }

    // 验证完毕，开始插入数据
    $insertArr = array();
    $insertArr['uin'] = $uin;
    $insertArr['voucher_id'] = $seleId;
    $insertArr['select_date'] = time();
    $insertArr['status'] = 0;
    $insertArr['friend_help_num'] = 0;
    $insertArr['myip'] = getIp();
    $obj->use_db('write');
    $obj->insert('ll_51_help_voucher_select', $insertArr);
    $status = $obj->db->affected_rows;
    if (!$status) {
        $result['msg'] = 'll_51_help_voucher_select insert error';
        $result['code'] = 5118;
        logLn("uin:{$uin} insert ll_51_help_voucher_select  error");
        echo json_encode($result);exit;
    }
    $result['msg'] = 'success';
    $result['code'] = 1;
    $result['data']['id'] = $obj->next_id();
    echo json_encode($result);exit;
}


// 展示逻辑
// 用户选中2张券
if (count($seleData) == 2) {
    $result['data']['voucherList'] = array();
    $voucherId1 = $seleData[0]['voucher_id'] -1;
    $result['data']['voucherList'][0]['id'] = $seleData[0]['id'];
    $result['data']['voucherList'][0]['voucherId'] = $seleData[0]['voucher_id'];
    $result['data']['voucherList'][0]['name'] = $helpArr[$voucherId1]['name'];
    $result['data']['voucherList'][0]['image'] = $helpArr[$voucherId1]['image'];
    $result['data']['voucherList'][0]['help_times'] = $helpArr[$voucherId1]['help_times'];
    $result['data']['voucherList'][0]['buttStatus'] = 2;

    $voucherId2 = $seleData[1]['voucher_id'] -1;
    $result['data']['voucherList'][1]['id'] = $seleData[1]['id'];
    $result['data']['voucherList'][1]['voucherId'] = $seleData[1]['voucher_id'];
    $result['data']['voucherList'][1]['name'] = $helpArr[$voucherId2]['name'];
    $result['data']['voucherList'][1]['image'] = $helpArr[$voucherId2]['image'];
    $result['data']['voucherList'][1]['help_times'] = $helpArr[$voucherId2]['help_times'];
    $result['data']['voucherList'][1]['buttStatus'] = 2;

}

// 用户选中1张券
if (count($seleData) == 1) {
    $result['data']['voucherList'] = array();
    $id = $seleData[0]['voucher_id'] - 1;
    $index = 0;
    $result['data']['voucherList'][$index]['id'] = $seleData[0]['id'];  //标识该记录的id
    $result['data']['voucherList'][$index]['voucherId'] = $helpArr[$id]['id']; // 代金券id
    $result['data']['voucherList'][$index]['name'] = $helpArr[$id]['name'];
    $result['data']['voucherList'][$index]['image'] = $helpArr[$id]['image'];
    $result['data']['voucherList'][$index]['help_times'] = $helpArr[$id]['help_times'];
    $result['data']['voucherList'][$index]['buttStatus'] = 2;

    foreach ($helpArr as $k => $v) {
        if ($v['id'] != $seleData[0]['voucher_id']) {
            $index++;
            $result['data']['voucherList'][$index]['id'] = '';
            $result['data']['voucherList'][$index]['voucherId'] = $v['id'];
            $result['data']['voucherList'][$index]['name'] = $v['name'];
            $result['data']['voucherList'][$index]['image'] = $v['image'];
            $result['data']['voucherList'][$index]['help_times'] = $v['help_times'];
            if ($v['fangshua'] == 1) {
                // 开启了防刷
                $result['data']['voucherList'][$index]['buttStatus'] = 3;
            } else {
                // 选的第1张券若是在进行中，则其他券不可点击
                if ($seleData[0]['status'] == 0) {
                    $result['data']['voucherList'][$index]['buttStatus'] = 4; // 4 表示"灰色选我"
                } else {
                    $result['data']['voucherList'][$index]['buttStatus'] = 1;  // 1表示"红色选我"
                }
            }
        }
    }
}

$result['code'] = 1;
echo json_encode($result);



















