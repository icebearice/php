<?php
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/config.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';

$result = array(
    'code' => 0,
    'msg' => '',
    'data' => array(),
);

$time = time();
if ($time > $endTime) {
    $response['code'] =	2003; 
    $response['msg'] = '活动已结束';
    echo json_encode($response);
    exit();
}
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
if (!is_numeric($uin) || !$uin) {
    $result['code'] = 1001;
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
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
if ($action == 'get') {
    $obj->use_db('read');
    $sql = "SELECT uid, prize_first, prize_second FROM ll_fools_user_prize WHERE uid = '{$uin}'";
    $data = $obj->query($sql);
    if ($data) {
        $result['data'] = array($data[0]['prize_first'], $data[0]['prize_second']);
    }
    echo json_encode($result);
    exit;
} else if ($action == 'set') {
    $prize = isset($_REQUEST['prize']) ? $_REQUEST['prize'] : '';
    if (!$prize) {
        $response['code'] =	2001; 
        $response['msg'] = '参数错误';
        echo json_encode($response);
        exit();
    }
    $arr = explode(',', trim($prize));
    $prize_first = trim($arr[0]);
    $prize_second = trim($arr[1]);
    if (!isset($prizeArr[$prize_first]) || !isset($prizeArr[$prize_second])) {
        $response['code'] =	2002; 
        $response['msg'] = '参数错误';
        echo json_encode($response);
        exit();
    }
    $obj->use_db('write');
    $sql = "INSERT INTO ll_fools_user_prize (uid, prize_first, prize_second, add_time) VALUES ('{$uin}', '{$prize_first}', '{$prize_second}', '{$time}') ON DUPLICATE KEY UPDATE prize_first = '{$prize_first}', prize_second = '{$prize_second}'";
    $obj->query($sql);
    echo json_encode($result);
    exit;
}
