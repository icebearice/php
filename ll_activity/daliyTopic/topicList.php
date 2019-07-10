<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . '/flamingoBaseActivityLimitThriftManager.class.php';
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';
require_once dirname(__FILE__) . '/resultCode.php';
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_INCLUDE . '/global.php';
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
        'data' => array(
            'topic_list' => array(),
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
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$size = 10;
$start = $page*$size;
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$topicId = isset($_REQUEST['topicId']) ? $_REQUEST['topicId'] : 0;

$auth = new LLUserAuthServer();
$obj = new LLDaliyTopicManager();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) { //登录用户，需要判定是否点赞那些鬼东东
    $uin = 0;//登录失效，归0
}

$obj = new LLDaliyTopicManager();
if ($action=='history') {
    $start += 1; //去掉最新一期
    $topic_list = packageTopicList($obj->getTopic(0, $start, $size, 1), $uin,0);
} else {
    $topic_list = packageTopicList($obj->getTopic(0, 0, 1, 1, $topicId), $uin,0,1);
}
if (!$topic_list) {
    echo json_encode($response);
    exit;
}

$response['data']['topic_list'] = $topic_list;

if ($action != 'history' && isset($topic_list[0]['vote_info']) && isset($topic_list[0]['vote_info']['vote_id'])) {
    $db = new Db();
    $db->use_db('lldaliytopic');
    $sql = "SELECT vote_select FROM ll_daliy_vote_log WHERE uin = '{$uin}' AND vote_id = '{$topic_list[0]['vote_info']['vote_id']}'";
    $vote_before = $db->query($sql);
    if ($vote_before) {
        $response['data']['topic_list'][0]['vote_info']['choice'] = intval($vote_before[0]['vote_select']);
    }
}
echo json_encode($response);
exit;
