<?php
require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/config.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';

$result = array(
    'code' => 0,
    'msg' => '',
    'data' => array(),
);

$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;

$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $uin = 0;
}

$obj = new Db();
$obj->use_db('read');
$sql = "SELECT * FROM ll_fools_lottery WHERE uid = '{$uin}' ORDER BY id DESC";
$my = $obj->query($sql);
if ($my) {
    foreach ($my as $k => $v) {
        $result['data']['my'][] = array(
            'name' => $prizeArr[$v['prize_id']]['show_name'],
            'icon' => $prizeArr[$v['prize_id']]['icon'],
            'desc' => $prizeArr[$v['prize_id']]['desc'],
        );
    }
} else {
    $result['data']['my'] = array();
}

$sql = "SELECT * FROM ll_fools_push_prize WHERE uid = '{$uin}'";
$my_extend = $obj->query($sql);
if ($my_extend) {
    $result['data']['extend'] = array(
        'name' => $my_extend[0]['prize_name'],
        'desc' => $my_extend[0]['descript'],
    );
} else {
    $result['data']['extend'] = array();
}

$sql = "SELECT * FROM ll_fools_lottery ORDER BY id DESC";
$list = $obj->query($sql);
if ($list) {
    $userHandler = new LLUserInfoServer();
    foreach( $list as $k => $v ) {
        $userInfo = $userHandler->getUserInfoByUin($v['uid']);
        $userInfo = json_decode(json_encode($userInfo), true);
        $base_data = $userInfo['base_data'];
        $show_name = isset($base_data['unickname']) ? $base_data['unickname'] : (isset($base_data['uname']) ? $base_data['uname'] : $base_data['uphone']);
        $strlen = mb_strlen($show_name, 'utf-8');
        $firstStr= $strlen>0 ? mb_substr($show_name, 0, 1, 'utf-8') : '';
        $lastStr = $strlen>0 ? mb_substr($show_name, -1, 1, 'utf-8') : '';
        $result['data']['list']["prize_level_{$v['prize_level']}"][] = array(
            'name' => $prizeArr[$v['prize_id']]['show_name'],
            'icon' => $prizeArr[$v['prize_id']]['icon'],
            'desc' => $prizeArr[$v['prize_id']]['desc'],
            'uico' => isset($userInfo['ext_data']['uico']) ? $userInfo['ext_data']['uico'] : '',
            'unickname' => $firstStr . str_repeat("*",4) . $lastStr,
        );
    }
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
