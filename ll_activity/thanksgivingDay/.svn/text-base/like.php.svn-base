<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . '/flamingoBaseActivityLimitThriftManager.class.php';
require_once dirname(__FILE__) . '/config.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/TnCode.class.php';

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

$ip  = getIp();
$date = date('Y-m-d');
$reply_id = isset($_REQUEST['id'])&&is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : 0;

//2.0 如果有滑动验证，检测之
if ($OPEN_TNCODE) {
    $tncode = new TnCode();    
    if (!$tncode->check()) {
        $response['code'] = 1002;
        $response['err_msg'] = '验证码错误，请重试';
        echo json_encode($response);
        exit;
    }
}

//3.0 点赞每日限制一次，验证一下
$base_limit_obj = new BaseActivityLimitThriftManager();
$limit_like_info = $base_limit_obj->GetInfo($ACTIVITY_ID, $LIMIT_LIKE_KEY, $ip, 1, 1);
if( $limit_like_info && isset($limit_like_info['info']['field'][0]) && $limit_like_info['info']['field'][0]['outcome'] == 2 ) {
    $response['code'] = 1003;
    $response['err_msg'] = '该IP今日已点赞哦'; 
    echo json_encode($response);
    exit;
}

//4.0 开始入库
$db = new Db();
$db->use_db( 'write' );
$db->query('start transaction');
$insert_arr = array(
    'ip' => $ip,
    'like_date' => $date,
    'reply_id' => $reply_id,
    'addtime' => time(),
);
$db->insert( 'thanksgiving_day_like', $insert_arr );
$insert_rows = $db->db->affected_rows;

$sql = "UPDATE thanksgiving_day_reply SET like_times = like_times + 1 WHERE id = '{$reply_id}' AND status = 1";
$db->query($sql);
$update_rows = $db->db->affected_rows;
if ($insert_rows<=0 || $update_rows<=0 || !$db->query('commit')) {
    $db->query('rollback');
    $response['code'] = 1004;
    $response['err_msg'] = '失败，请重试';
    echo json_encode($response);
    exit;
}

//5.0 上报防刷限制
$report_arr = array(
    0 => array('name'=>$LIMIT_LIKE_KEY, 'value'=>$ip,),
);
$base_limit_obj->ReportInfo($ACTIVITY_ID, $report_arr);
echo json_encode($response);
exit;
