<?php
//获取普通通知
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once dirname(__FILE__) . '/resultCode.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/DB.php';
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
            'message_list' => array(),
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
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$size = 15;
$start = $page*$size;

$auth = new LLUserAuthServer();
$obj = new LLDaliyTopicManager();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) { //登录用户，需要判定是否点赞那些鬼东东
    $response['code'] = 503;
    $response['err_msg'] = '';
    echo json_encode($response);
    exit;
}

$db = new Db();
$db->use_db('llmessagecenter');
$sql = "SELECT * FROM push_info WHERE uin_lists = '{$uin}' AND tag_str = '话题' AND list_type = 0 ORDER BY id DESC LIMIT {$start},{$size}";
$message_info = $db->query($sql);
$return_message_list = array();
if ($message_info) {
    foreach($message_info as $k => $v) {
        $return_message_list[$k]['id'] = $v['id'];    
        $return_message_list[$k]['title'] = $v['title'];
        $return_message_list[$k]['content'] = isset($v['contentText']) ? $v['contentText'] : '';
        $return_message_list[$k]['time'] = strtotime($v['createTime']);
        $return_message_list[$k]['tag'] = isset($v['tag_str']) ? $v['tag_str'] : '';
        $return_message_list[$k]['jumpUrl'] = isset($v['webUrl']) && $v['webUrl'] ? $v['webUrl'] : '';
    }
}
/*
$obj = new LLGetMessageCenter($MESSAGE_CENTER_URL);
$message_list = $obj->getUserMessageList($uin,$start,$size,0);
if ($message_list && $message_list['result'] == 0) {
    $message_list = isset($message_list['list_res']['messages']) ? $message_list['list_res']['messages'] : array();
}
$return_message_list = array();
if ($message_list) {
    foreach($message_list as $k => $v) {
        $return_message_list[$k]['id'] = $v['id'];
        $return_message_list[$k]['title'] = $v['title'];
        $return_message_list[$k]['content'] = isset($v['msg_string']) ? $v['msg_string'] : '';
        $return_message_list[$k]['time'] = $v['timestamp'];
        $return_message_list[$k]['tag'] = isset($v['tag_str']) ? $v['tag_str'] : '';
        $return_message_list[$k]['jumpUrl'] = isset($v['action']) && isset($v['action']['url']) && $v['action']['url'] ? $v['action']['url'] : '';
    } 
}*/

$response['data']['message_list'] = $return_message_list;
echo json_encode($response);
exit;
