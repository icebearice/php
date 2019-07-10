<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/flamingoBaseActivityLimitThriftManager.class.php';
require_once SYSDIR_UTILS . '/flamingoDingDingMessage.class.php';
require_once dirname(__FILE__) . '/config.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/TnCode.class.php';
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . '/userInfoServer.class.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> '',
);

//1.0 时间判定
$time = time();
if( $time > 1544111999 ) { //2018-11-25 23:59:59 结束
    $response['code'] = 1001;
    $response['err_msg'] = '活动已于11.25正式结束感谢您的关注';
    echo json_encode($response);
    exit();
}

$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
$code = isset($_RQUEST['code']) ? $_REQUEST['code'] : '';
$ip  = getIp();
$date = date('Y-m-d');

//2.0 检查登录
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $response['code'] =	ErrorCode::User_Not_Login; 
    $response['err_msg'] = ErrorCode::getTaskError($response['code']);
    echo json_encode($response);
    exit();
}

//3.0 如果有滑动验证，检测之
if ($OPEN_TNCODE) {
    $tncode = new TnCode();    
    if (!$tncode->check()) {
        $response['code'] = 1002;
        $response['err_msg'] = '验证码错误，请重试';
        echo json_encode($response);
        exit;
    }
}

//4.0 账号发表回复检测
$base_limit_obj = new BaseActivityLimitThriftManager();
$limit_reply_info = $base_limit_obj->GetInfo($ACTIVITY_ID, $LIMIT_REPLY_KEY, $uin, 1, 1);
if( $limit_reply_info && isset($limit_reply_info['info']['field'][0]) && $limit_reply_info['info']['field'][0]['outcome'] == 2 ) { //账号每天只能一次回复
    $response['code'] = 1003;
    $response['err_msg'] = '今日已发表回复哦';
    echo json_encode($response);
    exit;
}

//5.0 ip发表回复检测
$limit_reply_ip = $base_limit_obj->GetInfo($ACTIVITY_ID, $LIMIT_REPLY_IP_KYE, $ip, 1, 1);
if( $limit_reply_ip && isset($limit_reply_ip['info']['field'][0]) && $limit_reply_ip['info']['field'][0]['outcome'] == 2 ) { //ip每天只能一次回复
    $response['code'] = 1004;
    $response['err_msg'] = '该IP今日已发表回复哦';
    echo json_encode($response);
    exit;
} 

//6.0 检测内容
if (!$content) {
    $response['code'] = 1005;
    $response['err_msg'] = '回复内容不得为空哦';
    echo json_encode($response);
    exit;
}
if (!checkContentLen($content)) {
    $response['code'] = 1006;
    $response['err_msg'] = '回复内容需在10-140个字';
    echo json_encode($response);
    exit;
}
$check_res = $base_limit_obj->CheckInfo($content);    
if ($check_res) {
    $response['code'] = 1007;
    $str = '';
    foreach( $check_res as $k => $v ) {
        $str .= " {$v['word']}";
    }
    $response['err_msg'] = '回复内容包含敏感词';
    echo json_encode($response);
    exit;
}

//7.0 开始入库
$sign = md5($uin.$date.$content.time());
$insert_arr = array(
    'uid' => $uin,
    'reply_date' => $date,
    'reply_content' => $content,
    'like_times' => 0,
    'add_time' => time(),
    'sign' => $sign,
    'status' => 1,
);
$db = new Db();
$db->use_db( 'write' );
$db->insert( 'thanksgiving_day_reply', $insert_arr );
$rows = $db->db->affected_rows;
if ($rows<=0) {
    $response['code'] = 1008;
    $response['err_msg'] = '出现未知错误 请重试';
    echo json_encode($response);
    exit;
}
$id = $db->next_id();

//8.0 上报防刷限制
$report_arr = array(
    0 => array('name'=>$LIMIT_REPLY_KEY, 'value'=>$uin,),
    1 => array('name'=>$LIMIT_REPLY_IP_KYE, 'value'=>$ip,),
);
$base_limit_obj->ReportInfo($ACTIVITY_ID, $report_arr);

//9.0 钉钉机器通知有需要审核的回复啦...
$message_obj = new flamingoDingDingMessage($DINGDING_ROBOT_URL);
try {
    $user_obj = new LLUserInfoServer();
    $info = $user_obj->getUserInfoByUin($uin);
    if (!$info) {
        $unickname = '未知';
    } else {
        $unickname = $info->getBase_data()->getUnickname() ? $info->getBase_data()->getUnickname() : ($info->getBase_data()->getUphone() ? $info->getBase_data()->getUphone() : $info->getBase_data()->getUname());
    }
    $send_arr = array(
        '用户id' => $uin,
        '用户昵称' => $unickname,
        '回复内容' => $content,
    );
    //$ok_url = $AUDIT_URL . "?id={$id}&sign={$sign}&result=1";
    $ok_url = '';
    $reject_url = $AUDIT_URL . "?id={$id}&sign={$sign}&result=2";
    $message_obj->send_option_msg($AUDIT_TITLE, $send_arr, $ok_url, $reject_url);
} catch (Exception $e) {

}

echo json_encode($response);
exit;

function checkContentLen($content) {
    $len = (mb_strlen($content, 'UTF-8') + strlen($content)) /2;
    if ($len >280 ||  $len< 20) {
        return false;
    }
    return true;
}
