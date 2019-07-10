
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/14
 * Time: 14:18
 * 显示详情
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
$now=date("Y-m-d");

$obj = new Db();
$obj->use_db('read');
$sql = "SELECT * FROM ll_duanwu_help_voucher_select WHERE uin=$uin and select_date='{$now}'";
$seleData = $obj->query($sql);

if (empty($seleData)) {
    $result['msg'] = 'no this rows';
    $result['code'] = 51190;
    echo json_encode($result);exit;
}


// 助力次数
$result['data']['friendHelpNum'] = $seleData[0]['friend_help_num'];
$result['data']['count_of_nuo_mi']=$seleData[0]['count_of_nuo_mi'];
$result['data']['uin']=$uin;
$result['data']['select_date']=$seleData[0]['select_date'];

$result['code'] = 1;
echo json_encode($result);exit;
