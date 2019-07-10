
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/14
 * Time: 16:43
 * 发起助力
 */

require_once dirname(dirname(dirname(__FILE__))) . '/include/config.php';
require_once dirname(__FILE__) . '/common/config.php';
require_once dirname(__FILE__) . '/common/func.php';
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . '/DB.php';
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . '/voucherServer.class.php';
require_once SYSDIR_UTILS . '/XXRequestBase.php';

$result = array(
    'code' => -1,
    'msg' => '',
    'data' => array(),
);

//是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    $result['msg'] = $time['msg'];
    echo json_encode($result);exit;
}



// 是否登录
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
$auth = new LLUserAuthServer();

if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {

    echo json_encode(array('code'=>503, 'msg'=>'您还没有登录'));
    exit;
}

// 已登录
$obj = new Db();
$obj->use_db('read');
$now=date('Y-m-d');
$seleSql = "SELECT * FROM ll_duanwu_help_voucher_select WHERE uin=$uin and select_date='{$now}'"; //查询是否已经发起助力
$seleData = $obj->query($seleSql);
// 如果发起助力则回显助力
if(!empty($seleData)){

    $result['data']['friendHelpNum'] = $seleData[0]['friend_help_num'];
    $result['data']['count_of_nuo_mi']=$seleData[0]['count_of_nuo_mi'];
    $result['data']['uin']=$seleData[0]['uin'];
    $result['data']['myip']=$seleData[0]['myip'];
    $result['code']=1;
    $result['msg']="success";
    echo json_encode($result);exit;
}
// 验证完毕，开始插入数据
$insertArr = array();
$insertArr['uin'] = $uin;
$insertArr['select_date'] = $now;
$insertArr['count_of_nuo_mi'] = 0;
$insertArr['friend_help_num'] = 0;
$insertArr['myip'] = getIp();
$obj->use_db('write');
$obj->insert('ll_duanwu_help_voucher_select', $insertArr);
$status = $obj->db->affected_rows;
if (!$status) {
    $result['msg'] = 'll_duanwu_help_voucher_select';
    $result['code'] = 5118;
    logLn("uin:{$uin} ll_duanwu_help_voucher_select  error");
    echo json_encode($result);exit;
}
$result['msg'] = 'success';
$result['code'] = 1;
$insertArr['id']=$obj->next_id();
$result['data'] = $insertArr;
echo json_encode($result);exit;
