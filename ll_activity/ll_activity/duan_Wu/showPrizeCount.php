<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 18:26
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
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $result['code'] = ErrorCode::User_Not_Login;
    $result['msg'] ="未登录";
    echo json_encode($result);
    exit();
}
$now=date('Y-m-d');
$obj = new Db();
$obj->use_db('read');
$sql = "SELECT zongzi_num FROM ll_duanwu_user_prize_count WHERE  uin={$uin} AND active_date='{$now}'";
$userNum = $obj->query($sql);
if (!isset($userNum[0]['zongzi_num']) || $userNum[0]['zongzi_num'] < 1) {
    $result['msg'] = '粽子数不够';
    $result['code'] = 1;
    $result['data']['count_of_zongzi']=0;
    echo json_encode($result);
    exit();
}
$result['msg']="剩余粽子数";
$result['code']=1;
$result['data']['count_of_zongzi']=$userNum[0]['zongzi_num'];
echo  json_encode($result);
exit();
