<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once dirname(__FILE__) . '/errorCode.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/LLGameDataManager.php';
require_once SYSDIR_UTILS . '/LLGameBoardManager.class.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
);
$shareUin = isset($_REQUEST['shareUin'])&&is_numeric($_REQUEST['shareUin']) ? $_REQUEST['shareUin'] : 0;
$gid = isset($_REQUEST['gid'])&&is_numeric($_REQUEST['gid']) ? $_REQUEST['gid'] : 0;
$ip = getIp();
if (!$gid) {
    $response['code'] = 202;    
    $response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
    echo json_encode($response);
    exit;
}

$obj = new LLGameDataManager();
$data = $obj->getGameInfo($gid);
if (!$data || $data[0]['status']!=1) {
    $response['code'] = 203;    
    $response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
    echo json_encode($response);
    exit;
}
$gameInfo = $data[0];

$boardObj = new LLGameBoardManager();
$res = $boardObj->hitCall($gameInfo['appid'], $shareUin, $ip);
$response['code'] = $res['code'];
$response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
echo json_encode($response);
exit;
