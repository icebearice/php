<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once dirname(__FILE__) . '/errorCode.php';
require_once SYSDIR_UTILS . '/LLGameBoardManager.class.php';
require_once SYSDIR_UTILS . '/LLGameDataManager.php';
require_once SYSDIR_UTILS . '/LLGameCommunityManager.class.php';
require_once SYSDIR_UTILS . '/userInfoServer.class.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> array('appid'=>0,),
);
$bid = isset($_REQUEST['bid'])&&is_numeric($_REQUEST['bid']) ? $_REQUEST['bid'] : 0;
$gid = isset($_REQUEST['gid'])&&is_numeric($_REQUEST['gid']) ? $_REQUEST['gid'] : 0;
if (!$bid || !$gid) {
    $response['code'] = 204;    
    $response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
    echo json_encode($response);
    exit;
}

//1.0 游戏信息
$gameObj = new LLGameDataManager();
$gameInfo = $gameObj->getGameInfo($gid);
if (!$gameInfo || $gameInfo[0]['status']!=1) {
    $response['code'] = 201;
    $response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
    echo json_encode($response);
    exit;
}

//2.0 获取榜单信息
$obj = new LLGameBoardManager();
$data = $obj->getBoard(array($bid), 0, 1);
if ($data && $data[0]['status']==1) {
    $response['data'] = $data[0];
    $response['isIng'] = $data[0]['end_time'] >= time() ? 1 : 0;
    $ranking = $obj->getRanking($bid, $gameInfo[0]['appid'], 0, 1);
    $response['data']['ranking'] = isset($ranking[0]) ? $ranking[0] : array();
    $communityObj = new LLGameCommunityManager();
    $comments = $communityObj->getComment(0, 0, 2, 1, $gameInfo[0]['appid']);
    if ($comments) {
        $userObj = new LLUserInfoServer();
        foreach($comments as $k => $v) {
            $userInfo = json_decode(json_encode($userObj->getUserInfoByUin($v['uid'])), true);
            $comments[$k]['unickname'] = $userInfo && isset($userInfo['base_info']['unickname']) ? $userInfo['base_data']['unickname'] : '';
        }
    }
    $response['data']['comments'] = $comments;
} else {
    $response['code'] = 205;
    $response['err_msg'] = $DEFAULT_ERROR_RESULT[$response['code']];
    echo json_encode($response);
    exit;
}

echo json_encode($response);
exit;
