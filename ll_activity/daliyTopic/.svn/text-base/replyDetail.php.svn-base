<?php
//评论详情页
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
	    'second_reply_list' => array(),
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
$page = isset($_REQUEST['page'])&&is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$size = 15;
$start = $page*$size;
$reply_id = isset($_REQUEST['replyId']) ? $_REQUEST['replyId'] : 0;

$auth = new LLUserAuthServer();
$obj = new LLDaliyTopicManager();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) { 
    //$response['code'] = 503;
    //echo json_encode($response);
    //exit;
}

$reply_list = $obj->getReply($reply_id, 0, 0, 1, 0, 1, 0, array('id'=>1,));
$response['data']['reply_info'] = packageReplyList($reply_list, $uin,0);

$second_reply_list = $obj->getSecondReply($reply_id,$start,$size,0,array('id'=>0));
$response['data']['second_reply_list'] = packageSecondReplyList($second_reply_list,$uin);
echo json_encode($response);
exit;
