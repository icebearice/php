
<?php
/**
 * Created by PhpStorm.
 * User: haian.jin
 * Date: 2019/5/17
 * Time: 18:27
 * 展示我的奖品
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
    $response['code'] = ErrorCode::User_Not_Login;
    $response['msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$allData = array();
$xh = 0;
$obj = new Db();
$obj->use_db('read');

// 抽奖模块
$sqlPrize = "SELECT *  FROM ll_duanwu_user_prize WHERE uin={$uin} and status=1 ORDER BY last_up_date  desc";
$prizeData = $obj->query($sqlPrize);

// 兑换模块
$sqlHelp = "SELECT * FROM ll_duanwu_exchange_voucher WHERE uin={$uin} and status=1 ORDER BY last_up_date desc";
$helpData = $obj->query($sqlHelp);
if (empty($prizeData) && empty($consData) && empty($helpData) ) {
    echo json_encode(array('code'=>1, 'msg'=>'无数据'));
    exit;
}
//展示抽奖部分

if (is_array($prizeData) && !empty($prizeData)) {
    foreach ($prizeData as $k => $v) {
        $index = $v['prize_id'] - 1;
        $allData[$xh]['prize_id'] =$v['prize_id'];
        $allData[$xh]['prizeDate'] =$v['last_up_date'];
        $getVoucherDate=strtotime($v['last_up_date']);
        $allData[$xh]['image'] = $prizeArr[$index]['image'];
        $allData[$xh]['num']=$prizeArr[$index]['num'];
        $allData[$xh]['condition']=$prizeArr[$index]['condition'];
        $allData[$xh]['type']=$prizeArr[$index]['type'];
        $allData[$xh]['name']=$prizeArr[$index]['name'];
        $allData[$xh]['typeName']='拆粽子';
        $outTime=$prizeArr[$index]['expired'];
        //计算过期时间
        $expired=date('Y-m-d H:i:s',$outTime+$getVoucherDate);
        $allData[$xh]['expired']=$expired;
        $xh++;
    }
}


//兑换部分

if (is_array($helpData) && !empty($helpData)) {
    foreach ($helpData as $k => $v) {
        $index = $v['Info_id'] - 1;
        $allData[$xh]['exchangeId']=$v['Info_id'];
        $allData[$xh]['typeName'] = '集糯米';
        $allData[$xh]['prizeDate'] =$v['last_up_date'];
        $getVoucherDate=strtotime($v['last_up_date']);
        $allData[$xh]['num']=$helpArr[$index]['num'];
        $allData[$xh]['condition']=$helpArr[$index]['condition'];
        $allData[$xh]['name']=$helpArr[$index]['name'];
        $allData[$xh]['image'] = $helpArr[$index]['image'];
        $outTime=$helpArr[$index]['expired'];
        //计算过期时间
        $expired=date('Y-m-d H:i:s',$outTime+$getVoucherDate);
        $allData[$xh]['expired']=$expired;
        $xh++;
    }
}

// 根据 prizeData 倒序
array_multisort(getColumn2Arr($allData, 'prizeDate'), SORT_DESC, $allData);


$result['code'] = 1;
$result['data'] = $allData;
echo json_encode($result);
exit;
