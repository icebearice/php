
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 15:26
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


foreach ($prizeArr as $k => $v) {
    $pArr = array();
    $prize_id = $v['id'];
    $name = $v['name'];
    $num = $v['num'];
    $condition = $v['condition'];
    $image = $v['image'];
    $pArr['prize_id']=$prize_id;
    $pArr['name']=$name;
    $pArr['num']=$num;
    $pArr['condition']=$condition;
    $pArr['type']=$v['type'];
    $pArr['image']=$image;
    $result['data'][]=$pArr;
}
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $result['code'] = ErrorCode::User_Not_Login;
    $result['msg'] ="未登录";
    echo json_encode($result);
    exit();
}
$result['code']=1;
echo  json_encode($result);
exit();
