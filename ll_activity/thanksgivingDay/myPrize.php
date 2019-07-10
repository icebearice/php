<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/flamingoBaseActivityLimitThriftManager.class.php';
require_once SYSDIR_UTILS . '/flamingoDingDingMessage.class.php';
require_once dirname(__FILE__) . '/config.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/TnCode.class.php';
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/userInfoServer.class.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> '',
);

$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
$code = isset($_RQUEST['code']) ? $_REQUEST['code'] : '';

//2.0 检查登录
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $response['code'] =	ErrorCode::User_Not_Login; 
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$db = new Db();
$db->use_db( 'read' );
$sql = "SELECT pid, get_time FROM thanksgiving_day_prize_log WHERE uid = '{$uin}' ORDER BY id DESC";
$data = $db->query($sql);
if (!$data) {
    echo json_encode($response);
    exit;
}

$db->use_db('llpay');
$sql = "SELECT id, vname, money, min_order_amount FROM pay_voucher WHERE id IN (" . implode(',', $DEFAULE_VOUCHER_ID) . ')';
$info = $db->query($sql);
$voucher_arr = array();
foreach( $info as $k => $v ) {
    $voucher_arr[$v['id']] = $v;
}
    
$res = array();
foreach ($data as $k => $v) {
    $min_order_amount = intval($voucher_arr[$v['pid']]['min_order_amount']);
    $money = intval($voucher_arr[$v['pid']]['money']);
    $res[] = array(
        //'name' => $voucher_arr[$v['pid']]['vname'],
        'name' => "满{$min_order_amount}减{$money}",
        'get_time' => date('m-d', $v['get_time']),
    ); 
}
$response['data'] = $res;
echo json_encode($response);
exit;
