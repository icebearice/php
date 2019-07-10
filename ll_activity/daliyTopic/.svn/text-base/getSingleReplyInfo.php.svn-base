<?php
//获取单条评论内容

require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . '/flamingoBaseActivityLimitThriftManager.class.php';
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';
require_once dirname(__FILE__) . '/resultCode.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/TnCode.class.php';
if( RUN_MODE == 'production' || RUN_MODE == 'staging' ){
    require_once dirname(__FILE__) . '/production_config.php';
}elseif( RUN_MODE == 'development' ){
    require_once dirname(__FILE__) . '/development_config.php';
}
require_once SYSDIR_UTILS . '/userInfoServer.class.php';
require_once dirname(__FILE__) . '/common.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> array(
        'reply_info' => array(),
    ),
);

$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : (isset($_COOKIE['llusersessionid']) ? $_COOKIE['llusersessionid'] : '');
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
if ($uin <= 0 && $login_key) {
    $arr = explode('_', $login_key);
    $uin = isset($arr[2]) ? $arr[2] : 0;
}
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : md5("ll_web_login_{$_SERVER['HTTP_USER_AGENT']}");
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 151;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
$ip  = getIp();

$auth = new LLUserAuthServer();
$obj = new LLDaliyTopicManager();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) { 
    //$response['code'] = 503;
    //echo json_encode($response);
    //exit;
}

$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : array();
$reply_id = isset($ids[0]) && is_numeric($ids[0]) ? $ids[0] : 0;
$second_reply_id = isset($ids[1]) && is_numeric($ids[1]) ? $ids[1] : 0;
//var_dump($ids);
if (!$second_reply_id) {
    $reply_list = $obj->getReply($reply_id, 0, 0, 1, 0, 1, 0, array('id'=>1,));
    //被删除了 返回个错误码
    if (!$reply_list || empty($reply_list) || $reply_list[0]['status'] == 3) {
        $response['code'] = 1007;
        $response['err_msg'] = '该评论已被删除';
        echo json_encode($response);exit;
    }
    $response['data']['reply_info'] = packageReplyList($reply_list, $uin,0);
    echo json_encode($response);exit;
} else {
    $second_reply_list = $obj->getSecondReply(0,0,1,0,array('sort_num'=>1, 'id'=>1),$second_reply_id);
    $response['data']['reply_info'] = packageSecondReplyList($second_reply_list,$uin);
    echo json_encode($response);
    exit;
}
