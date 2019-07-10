<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/userInfoServer.class.php';

$result = array(
    'code' => 0,
    'msg' => '',
    'data' => '',
);
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;

$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $response['code'] =	ErrorCode::User_Not_Login; 
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$obj = new Db();
$obj->use_db('accountTrans');
$sql = "SELECT invite_uin FROm ll_invite_account_log WHERE share_uin = '{$uin}' ORDER BY id DESC";
$data = $obj->query($sql);
$res = array();
if ($data) {
    $user_obj = new LLUserInfoServer();
    foreach($data as $k => $v) {
        $info = $user_obj->getUserInfoByUin($v['invite_uin']);
        if (!$info) {
            continue;
        }
        $res[] = array(
            'uico' => $info->getExt_data()->getUico(),
            'unickname' => $info->getBase_data()->getUnickname() ? $info->getBase_data()->getUnickname() : ($info->getBase_data()->getUphone() ? $info->getBase_data()->getUphone() : $info->getBase_data()->getUname()),
        );    
    }
}
$result['data'] = $res;
echo json_encode($result);exit;
