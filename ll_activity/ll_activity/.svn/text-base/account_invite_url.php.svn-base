<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
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
if (!is_numeric($uin) || !$uin) {
    $result['code'] = 1001;
    $result['msg'] = "error params";
    echo json_encode($result);
    exit;
}

$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $response['code'] =	ErrorCode::User_Not_Login; 
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$user_obj = new LLUserInfoServer();
$info = $user_obj->getUserInfoByUin($uin);
if (!$info || strlen($info->getBase_data()->getUphone())<=0) {
    $response['code'] = ErrorCode::User_Not_Phone;
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

$arr = array(
    'uin' => $uin,
);
$cid = $platform==102 ? 201296 : 202088;
$result['data'] = SHARE_URL_PRE . "?channelid={$cid}&ext=" . urlencode(base64_encode(json_encode($arr))) . '#/land/';
echo json_encode($result);
