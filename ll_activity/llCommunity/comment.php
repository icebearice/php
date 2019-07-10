<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once dirname(__FILE__) . '/errorCode.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/LLGameCommunityManager.class.php';
require_once SYSDIR_UTILS . '/LLGameDataManager.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
);
$shareUin = isset($_REQUEST['shareUin'])&&is_numeric($_REQUEST['shareUin']) ? $_REQUEST['shareUin'] : 0;
$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
$gid = isset($_REQUEST['gid'])&&is_numeric($_REQUEST['gid']) ? $_REQUEST['gid'] : 0;
$ip = getIp();
if (!$shareUin || !$content || !$gid) {
    $response['code'] = 202;    
    $response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
    echo json_encode($response);
    exit;
}

$obj = new LLGameDataManager();
$gameInfo = $obj->getGameInfo($gid);
if (!$gameInfo) {
    $response['code'] = 203;    
    $response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
    echo json_encode($response);
    exit;
}

$communityObj = new LLGameCommunityManager();
$res = $communityObj->addComment($shareUin, $gameInfo[0]['appid'], $ip, $content);
$response['code'] = $res['result'] > 0 ? $res['result']+1000 : intval($res['result']);
$response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
echo json_encode($response);
exit;
