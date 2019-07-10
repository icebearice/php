<?php
//热门评论
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
	'reply_list' => array(),
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
$topicId = isset($_REQUEST['topicId']) ? $_REQUEST['topicId'] : 0;
$page = isset($_REQUEST['page'])&&is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$size = isset($_REQUEST['size']) && is_numeric($_REQUEST['size']) ? $_REQUEST['size'] : 10;
$start = $page*$size;

$auth = new LLUserAuthServer();
$obj = new LLDaliyTopicManager();
if ($auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) { //登录用户 返回我的回复信息
} else {
    $uin = 0;//登录失效，归0
}

if ($page == 1) {
    $start = 2;
}
$reply_list = $obj->getReply(0, 0, $start, $size, $topicId, 1, 0,array('sort_num'=>1, 'id'=>1),1);
$response['data']['reply_list'] = packageReplyList($reply_list, $uin);
echo json_encode($response);
