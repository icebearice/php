<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . '/flamingoBaseActivityLimitThriftManager.class.php';
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';
require_once dirname(__FILE__) . '/resultCode.php';
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
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) { //登录用户，需要判定是否点赞那些鬼东东
    $response['code'] = 503;
    $response['err_msg'] = "尚未登录";
    echo json_encode($response);
    exit;
}
$vote_id = isset($_REQUEST['vote_id']) ? $_REQUEST['vote_id'] : 0;
$select = isset($_REQUEST['choice']) ? $_REQUEST['choice'] : 1;
$obj = new LLDaliyTopicManager();
$res = $obj->addVote($uin,$vote_id,$select);

$response['code'] = $res;

$topic_id = isset($_REQUEST['topic_id']) ? $_REQUEST['topic_id'] : 0;
$get_vote_info = $obj->getVoteInfo($topic_id);
if (!$get_vote_info) {
    echo json_encode($response);
    exit;
}
$vote_info = array();
if (is_array($get_vote_info)) {
    if (!$get_vote_info['all_vote_count']) {
        $left_p = 50;
        $right_p = 50;
    } else {
        $left_p = $get_vote_info['first_vote_count'] / $get_vote_info['all_vote_count'] * 100;
        $right_p = $get_vote_info['second_vote_count'] / $get_vote_info['all_vote_count'] * 100;
    }
    $vote_info = array(
            'vote_id' => $get_vote_info['id'],
            'votes' => array(
                0 => (int)$left_p,
                1 => intval(100 - (int)$left_p),
                ),
            'exist' => true,
            'allow' => true,
            'title' => isset($get_vote_info['description']) ? $get_vote_info['description'] : '',
            'options' => array(
                isset($get_vote_info['button_1']) ? $get_vote_info['button_1'] : '',
                isset($get_vote_info['button_2']) ? $get_vote_info['button_2'] : '',
                ),
            'choice'=> (int)$select,
            );
}

$response['data']['vote_info'] = $vote_info;
//$response['data']['topic_list'] = $topic_list;
echo json_encode($response);
exit;
