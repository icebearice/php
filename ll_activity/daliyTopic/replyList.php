<?php
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
	'my_reply' => array(),
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
$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
$code = isset($_RQUEST['code']) ? $_REQUEST['code'] : '';
$ip  = getIp();
$topicId = isset($_REQUEST['topicId']) ? $_REQUEST['topicId'] : 0;
$page = isset($_REQUEST['page'])&&is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$size = 10;
$start = $page*$size;
$shareUin = isset($_REQUEST['shareUin']) ? $_REQUEST['shareUin'] : 0;
$my_reply_is_hot_reply = 0;
$auth = new LLUserAuthServer();
$obj = new LLDaliyTopicManager();
$topicInfo = $obj->getTopic(0, 0, 1, 1, 0);
if ($auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) { //登录用户 返回我的回复信息
    $my_reply = $obj->getReply(0, $uin, 0, 1, $topicId ? $topicId : $topicInfo[0]['id'], 1, 0, array('id'=>1,));
    $response['data']['my_reply'] = packageReplyList($my_reply, $uin);
    if (isset($response['data']['my_reply'][0]['is_hot_reply']) && $response['data']['my_reply'][0]['is_hot_reply'] == 1) {
        //$response['data']['my_reply'] = array(); 
        $my_reply_is_hot_reply = 1;
    }
} else {
    $uin = 0;//登录失效，归0
}

if ($shareUin>0) {
    $reply_list = $obj->getReply(0, $shareUin, $start, $size, $topicId ? $topicId : $topicInfo[0]['id'], 1);
} else {
    if ($my_reply_is_hot_reply) {
        $reply_list = $obj->getReply(0, 0, $start, $size, $topicId ? $topicId : $topicInfo[0]['id'], 1,$uin,array('like_times'=>1, 'id'=>1),2);
    } else {
        if ($page == 0 && $uin) {
            //$reply_list = $my_reply;
            $reply_list = $obj->getReply(0, 0, $start, $size, $topicId ? $topicId : $topicInfo[0]['id'], 1, $uin,array('like_times'=>1, 'id'=>1),2);
            $reply_list = array_merge($my_reply,$reply_list);
        } else {
            $reply_list = $obj->getReply(0, 0, $start, $size, $topicId ? $topicId : $topicInfo[0]['id'], 1, $uin,array('like_times'=>1, 'id'=>1),2);
        }
    }
}
$response['data']['reply_list'] = packageReplyList($reply_list, $uin,1,0);
echo json_encode($response);
exit;
