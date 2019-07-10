<?php
//回复别人的回复接口
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';
require_once dirname(__FILE__) . '/resultCode.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/TnCode.class.php';
if( RUN_MODE == 'production' || RUN_MODE == 'staging' ){
    require_once dirname(__FILE__) . '/production_config.php';
}elseif( RUN_MODE == 'development' ){
    require_once dirname(__FILE__) . '/development_config.php';
}

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> '',
);

$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : (isset($_COOKIE['llusersessionid']) ? $_COOKIE['llusersessionid'] : '');
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
if ($uin <= 0 && $login_key) {
    $arr = explode('_', $login_key);
    $uin = $arr[2];
}
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : md5("ll_web_login_{$_SERVER['HTTP_USER_AGENT']}");
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 151;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
$ip  = getIp();
$date = date('Y-m-d');
$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : array();
$reply_id = isset($ids[0]) && is_numeric($ids[0]) ? $ids[0] : 0;
$second_reply_id = isset($ids[1]) && is_numeric($ids[1]) ? $ids[1] : 0;
//$reply_id = isset($_REQUEST['replyId']) ? $_REQUEST['replyId'] : 0;
//$second_reply_id = isset($_REQUEST['secondReplyId']) ? $_REQUEST['secondReplyId'] : 0;

//1.0 检查登录
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $response['code'] =	503; 
    $response['err_msg'] = $DALIY_RESULT_CODE[$response['code']];
    echo json_encode($response);
    exit();
}

//2.0 检测内容
if (!$content) {
    $response['code'] = 504;
    $response['err_msg'] = $DALIY_RESULT_CODE[$response['code']];
    echo json_encode($response);
    exit;
}
if (!checkContentLen($content)) {
    $response['code'] = 505;
    $response['err_msg'] = $DALIY_RESULT_CODE[$response['code']];
    echo json_encode($response);
    exit;
}

//3.0 开始发布回复
$obj = new LLDaliyTopicManager();
$res = $obj->addSecondReply($uin, $reply_id,$second_reply_id, $content, $ip,1);
$response['code'] = $res;
$response['err_msg'] = $DALIY_RESULT_CODE[$response['code']];
echo json_encode($response);
exit;

function checkContentLen($content) {
    $len = (mb_strlen($content, 'UTF-8') + strlen($content)) /2;
    if ($len >6000 ||  $len< 10) {
        return false;
    }
    return true;
}
