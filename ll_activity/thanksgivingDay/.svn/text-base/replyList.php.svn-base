<?php
require_once dirname(dirname(__FILE__)) . '/include/config.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . '/flamingoBaseActivityLimitThriftManager.class.php';
require_once dirname(__FILE__) . '/config.php';
require_once SYSDIR_INCLUDE . '/global.php';
require_once SYSDIR_UTILS . '/userInfoServer.class.php';

$response = array(
    'code'=> 0,
    'err_msg' => '',
    'data'=> array(
        'open_tncode' => $OPEN_TNCODE,
        'list_info' => array(),    
        'my_reply' => array(),
        //'befor_reply' => array(),
        'ip_already_reply' => 0,
        'my_reply_auditing' => 0,
    ),
);

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
$share_uin = isset($_REQUEST['share_uin']) ? $_REQUEST['share_uin'] : 0;
$page = isset($_REQUEST['page'])&&is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$size = 15;
$start = $page*$size;
$ip = getIp();
$date = date( 'Y-m-d' );

$db = new Db();
$db->use_db( 'read' );

$sql = "SELECT ip, like_date, reply_id FROM thanksgiving_day_like WHERE ip = '{$ip}'";
$data = $db->query( $sql );
if( $data ) {
    foreach( $data as $k => $v ) {
        $like_list[$v['reply_id']] = $v; 
    }
} else {
    $like_list = array();
}

$db->use_db('llpay');
$sql = "SELECT id, vname, money FROM pay_voucher WHERE id IN (" . implode(',', $DEFAULE_VOUCHER_ID) . ')';
$info = $db->query($sql);
$voucher_arr = array();
foreach( $info as $k => $v ) {
    $voucher_arr[$v['id']] = $v;
}

$db->use_db('read');
$auth = new LLUserAuthServer();
$base_limit_obj = new BaseActivityLimitThriftManager();
$user_obj = new LLUserInfoServer();
if ($auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) { //登录用户 返回我的回复信息
    $limit_reply_info = $base_limit_obj->GetInfo($ACTIVITY_ID, $LIMIT_REPLY_KEY, $uin, 1, 1);
    if( $limit_reply_info && isset($limit_reply_info['info']['field'][0]) && $limit_reply_info['info']['field'][0]['outcome'] == 2 ) { //一天只能一次回复，这里已经有了，说明有回复
        $sql = "SELECT id, uid, reply_date, reply_content, like_times, excellent_mark, excellent_prize, add_time, status FROM thanksgiving_day_reply WHERE uid = '{$uin}' AND reply_date = '{$date}'"; 
        $my_reply = $db->query( $sql );
        $res_my_reply = array();
        if ($my_reply) {
            foreach( $my_reply as $k => $v ) {
                if ($v['status']==2) {
                    continue;
                }
                if ($v['status']==0) {
                    $response['data']['my_reply_auditing'] = 1;
                    continue;
                }
                $res_my_reply[$k] = $v;
                $res_my_reply[$k]['is_like'] = isset($like_list[$v['id']]) ? 1 : 0;
                $res_my_reply[$k]['add_date'] = date('m/d H:i', $v['add_time']);
                $res_my_reply[$k]['user'] = getUserNickNameAndUico($user_obj->getUserInfoByUin($v['uid']));
                $res_my_reply[$k]['money'] = $v['excellent_prize'] > 0 ? intval($voucher_arr[$v['excellent_prize']]['money']) : 0;
            }
        }
        $response['data']['my_reply'] = $res_my_reply ? $res_my_reply[0] : array();
    }
}

if( $share_uin > 0 ) {
    $sql = "SELECT id, uid, reply_date, reply_content, like_times, excellent_mark, excellent_prize, add_time FROM thanksgiving_day_reply WHERE uid = '{$share_uin}' AND reply_date = '{$date}' AND status = 1";
} else {
    $sql = "SELECT id, uid, reply_date, reply_content, like_times, excellent_mark, excellent_prize, add_time FROM thanksgiving_day_reply WHERE uid != '{$uin}' AND reply_date = '{$date}' AND status = 1 ORDER BY excellent_mark DESC, like_times DESC, add_time DESC LIMIT {$start}, {$size}";
}
$list_info = $db->query($sql);

if( $list_info ) {
    foreach( $list_info as $k => $v ) {
        $list_info[$k]['is_like'] = isset($like_list[$v['id']]) ? 1 : 0;
        $list_info[$k]['add_date'] = date('m/d H:i', $v['add_time']);
        $list_info[$k]['user'] = getUserNickNameAndUico($user_obj->getUserInfoByUin($v['uid']));
        $list_info[$k]['money'] = $v['excellent_prize'] > 0 ? intval($voucher_arr[$v['excellent_prize']]['money']) : 0;
    }
}
$response['data']['list_info'] = $list_info ? $list_info : array();

/*
$sql = "SELECT id, uid, reply_date, reply_content, like_times, excellent_mark, add_time FROM thanksgiving_day_reply WHERE reply_date != '{$date}' AND status = 1 ORDER BY like_times DESC, add_time DESC";
$befor_list = $db->query($sql);
if( $befor_list ) {
    foreach( $befor_list as $k => $v ) {
        $befor_list[$k]['is_like'] = isset($befor_list[$v['id']]) ? 1 : 0;
        $befor_list[$k]['add_date'] = date('m/d H:i', $v['add_time']);
        $befor_list[$k]['user'] = getUserNickNameAndUico($user_obj->getUserInfoByUin($v['uid']));
    }
}
$response['data']['befor_list'] = $befor_list ? $befor_list : array();
 */

$limit_reply_ip = $base_limit_obj->GetInfo($ACTIVITY_ID, $LIMIT_REPLY_IP_KYE, $ip, 1, 1);
if( $limit_reply_ip && isset($limit_reply_ip['info']['field'][0]) && $limit_reply_ip['info']['field'][0]['outcome'] == 2 ) { //ip每天只能一次回复
    $response['data']['ip_already_reply'] = 1;
} 

echo json_encode($response);
exit;

function getUserNickNameAndUico($user) {
    $arr = json_decode(json_encode($user), true);
    $result = array(
        'nickname' => isset($arr['base_data']['unickname'])&&$arr['base_data']['unickname'] ? $arr['base_data']['unickname'] : (isset($arr['base_data']['uphone'])&&$arr['base_data']['uphone'] ? getShowName($arr['base_data']['uphone']) : $arr['base_data']['uname']),
        'uico' => isset($arr['ext_data']['uico']) ? $arr['ext_data']['uico'] : 'http://img.66shouyou.cn/2018-11-15/1542263952405.png',
    );
    return $result;
}

function getShowName($name) {
    if (strlen($name)<=0) {
        return '';
    }
    return mb_substr($name, 0, 2) . '***' . mb_substr($name, -1, 2);
}
