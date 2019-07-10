<?php
//举报接口
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . '/LLDaliyTopicManager.class.php';
require_once SYSDIR_UTILS . '/DB.php';
require_once dirname(__FILE__) . '/resultCode.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/DB.php';

if( RUN_MODE == 'production' || RUN_MODE == 'staging' ){
    require_once dirname(__FILE__) . '/production_config.php';
}elseif( RUN_MODE == 'development' ){
    require_once dirname(__FILE__) . '/development_config.php';
}

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> '',
);

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getTipsOff') {
    $db = new Db();
    $db->use_db('lldaliytopic');
    $sql = "SELECT id,tip_off_name FROM ll_tip_off_remark WHERE status = 1";
    $tips_off_info = $db->query($sql);
    $return_arr = array();
    if ($tips_off_info) {
        foreach($tips_off_info as $k => $v) {
            $return_arr[] = array(
                'reportType' => intval($v['id']),
                'tip_off_name' => $v['tip_off_name'],
            );
        }
    }
    $response['data'] = $return_arr;
    echo json_encode($response);
    exit;
}

$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : (isset($_COOKIE['llusersessionid']) ? $_COOKIE['llusersessionid'] : '');
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
if ($uin <= 0 && $login_key) {
    $arr = explode('_', $login_key);
    $uin = $arr[2];
}
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : md5("ll_web_login_{$_SERVER['HTTP_USER_AGENT']}");
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 151;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
$ip  = getIp();
$date = date('Y-m-d');
$topicId = isset($_REQUEST['topicId']) ? $_REQUEST['topicId'] : 0;

//1.0 检查登录
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $response['code'] =	503; 
    $response['err_msg'] = $DALIY_RESULT_CODE[$response['code']];
    echo json_encode($response);
    exit();
}

$choiceReportType = isset($_REQUEST['choiceReportType']) && is_array($_REQUEST['choiceReportType']) ? $_REQUEST['choiceReportType'][0] : 0;
$ids = isset($_REQUEST['commentIds']) && is_array($_REQUEST['commentIds']) ? $_REQUEST['commentIds'] : array();
$commentId = isset($ids[0]) && is_numeric($ids[0]) ? $ids[0] : 0;
$secondCommentId = isset($ids[1]) && is_numeric($ids[1]) ? $ids[1] : 0;
//$commentId = isset($_REQUEST['commentId']) && is_numeric($_REQUEST['commentId']) ? $_REQUEST['commentId'] : 0;
$reportContent = isset($_REQUEST['reportContent']) && $_REQUEST['reportContent'] ? $_REQUEST['reportContent'] : '';

$thrift_obj = new LLDaliyTopicManager();
$result = $thrift_obj->addTipOff($uin,$commentId,$choiceReportType,$reportContent,$secondCommentId,$ip);
if ($result) {
    $response['code'] = intval($result);
    $response['err_msg'] = $DALIY_RESULT_CODE[$result];
} else {
    $response['data'] = '感谢你的举报，小66收到啦~';
}
echo json_encode($response);
exit;


