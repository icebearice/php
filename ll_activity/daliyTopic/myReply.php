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
require_once dirname(__FILE__) . '/common.php';
require_once SYSDIR_UTILS . '/userInfoServer.class.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> '',
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
$date = date('Y-m-d');
$topicId = isset($_REQUEST['topicId']) ? $_REQUEST['topicId'] : 0;
$shareUin = isset($_REQUEST['shareUin']) ? $_REQUEST['shareUin'] : 0;
$shareTopicId = isset($_REQUEST['shareTopicId']) ? $_REQUEST['shareTopicId'] : 0;

//分享落地页数据
$obj = new LLDaliyTopicManager();
if ($shareUin > 0 && $shareTopicId > 0) {
    $share_reply = $obj->getReply(0, $shareUin, 0, 300, $shareTopicId, 1); //一个话题一个用户的回复不可能超过300条
    $response['data']['share_reply'] = packageReplyList($share_reply, $uin);
    $topicInfo = $obj->getTopic(0, 0, 1);
    $response['data']['is_newest'] = $topicInfo&&$topicInfo[0]['id']==$shareTopicId ? 1 : 0;
    echo json_encode($response);
    exit;
}

//获取我的回复
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $response['code'] =	503; 
    $response['err_msg'] = $DALIY_RESULT_CODE[$response['code']];
    echo json_encode($response);
    exit();
}
$page = isset($_REQUEST['page'])&&is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$size = 15;
$start = $page*$size;
$reply_list = packageReplyList($obj->getReply(0, $uin, $start, $size, 0, 1, 0, array('id'=>1,)), $uin);
$user_obj = new LLUserInfoServer();
foreach($reply_list as $k => $v) {
    $tmp = $obj->getTopic(0, 0, 1, 0, $v['topic_id']);
    $tmp = $tmp ? $tmp[0] : array();
    $topic_info = array();
    if ($tmp) {
        $topic_info['id'] = $tmp['id'];
        $topic_info['title'] = $tmp['title'];
        $topic_info['content'] = $tmp['content'];
        $topic_info['picture'] = isset($tmp['picture']) ? $tmp['picture'] : array();
        $topic_info['total_amount'] = $tmp['total_amount']/100;
        $topic_info['total_reply'] = $tmp['total_reply'];
        $topic_info['push_date'] = date('Y.m.d H:i', $tmp['push_time']);
        $topic_info['user'] = getUserNickNameAndUico($user_obj->getUserInfoByUin($tmp['uid']));
        $topic_info['status'] = $tmp['status'];
        if ($tmp['label_id']>0) {
            $topic_info['label_info'] = $obj->getLabelInfo(array($tmp['label_id']))[0];
        } else {
            $topic_info['label_info'] = array();
        }
    }
    $reply_list[$k]['topic_info'] = $topic_info;
}
$response['data']['my_reply'] = $reply_list;
echo json_encode($response);
exit;
