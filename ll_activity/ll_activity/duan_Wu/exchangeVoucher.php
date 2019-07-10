
<?php
/**
 * Created by PhpStorm.
 * User: haian.jin
 * Date: 2019/5/14
 * Time: 17:10
 * 兑换代金券
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
    'msg' => ''
);

//是否为活动时间
$time = isActivityTime($startTime, $endTime);
if (!$time['status']) {
    $result['code']=517;
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
$selectId=isset($_REQUEST['selectId'])?$_REQUEST['selectId']:0;

if (!$selectId){
    $result['code']=-1;
    $result['msg']='请选择代金券';
    echo json_encode($result);
    exit;
}

if($selectId<=0){
    $result['code']=-1;
    $result['msg']='选择代金券出错';
    echo json_encode($result);
    exit;
}

$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $result['code'] = 503;
    $result['msg']="用户未登陆";
    echo json_encode($result);exit;
}

// 已登录
$obj = new Db();
$obj->use_db('read');
$voucher_id=$helpArr[$selectId - 1]['voucher_id'];
$spend_nuo_mi=$helpArr[$selectId - 1]['spend_nuo_mi'];

if (!$voucher_id||!$spend_nuo_mi){
    $result['code']=544;
    $result['msg']='获取代金券数据异常';
    echo $result;
    exit;
}
//查询是否领取过该券
$seleSql="SELECT * FROM ll_duanwu_exchange_voucher  where uin=$uin and voucher_id=$voucher_id";
$seleData=$obj->query($seleSql);
if (!empty($seleData)){
    $result['code']=545;
    $result['msg']='一个人只能换一次该券';
    echo json_encode($result);
    exit;
}

$now=date('Y-m-d');
$seleSql = "SELECT * FROM ll_duanwu_help_voucher_select WHERE uin=$uin and select_date='{$now}'"; //查询当天是否已经发起助力
$seleData = $obj->query($seleSql);
if (empty($seleData)){
    $result['code']=543;
    $result['msg']='当天未发起助力，暂无糯米';
    echo json_encode($result);
    exit;
}

//获取糯米数
$count_of_nuo_mi=$seleData[0]['count_of_nuo_mi'];
if ($count_of_nuo_mi<$spend_nuo_mi){
    $result['code']=543;
    $result['msg']="糯米数不够";
    echo json_encode($result);
    exit;
}

//查看该代金券是否剩余
$seleSql="SELECT * FROM ll_duanwu_total_voucher_daily_num  WHERE voucher_id=$voucher_id and activity_date='{$now}'";
$data=$obj->query($seleSql);
if (empty($data)){
    $result['code']=546;
    $result['msg']="该代金券已过期";
    echo json_encode($result);
    exit;
}
$total_voucher=$data[0]['total_num'];
if (!$total_voucher){
    $result['code']=547;
    $result['msg']="该代金券已被抢光啦";
    echo $result;
    exit;
}
$obj->use_db('write');
$obj->query('start transaction');

//兑换出来
$vid=$data[0]['id'];
$seleSql="UPDATE ll_duanwu_total_voucher_daily_num  SET total_num=total_num-1 WHERE id=$vid and total_num>=1";
$obj->query($seleSql);
$updaRows = $obj->db->affected_rows;
if(!$updaRows){
    $obj->query('rollback');
    echo json_encode(array('code' => 51073, 'msg' => '操作频繁'));exit;
}

//减去糯米数
$seleSql="UPDATE ll_duanwu_help_voucher_select SET count_of_nuo_mi=count_of_nuo_mi-$spend_nuo_mi WHERE uin={$uin} and select_date='{$now}'";
$obj->query($seleSql);
$updaRows = $obj->db->affected_rows;
if(!$updaRows<0){
    $obj->query('rollback');
    echo json_encode(array('code' => 51073, 'msg' => '操作频繁'));exit;
}

//插入记录表
$sql="insert into ll_duanwu_exchange_voucher(uin,voucher_id,status,info_id) values($uin,$voucher_id,0,$selectId)";
$obj->query($sql);
$insertRows = $obj->db->affected_rows;
if($insertRows<0){
    $obj->query('rollback');
    echo json_encode(array('code' => 51073, 'msg' => '操作频繁'));exit;
}
$flag=$obj->query('commit');
if(!$flag){
    $obj->query('ROLLBACK');
    $result['msg']='操作频繁，请稍后重试';
    echo json_encode($result);
    exit;
}

// 下发代金券
$voucherObj = new LLVoucherServer();
$pushRes = $voucherObj->sendVoucher($uin, 'test-flamingo-login-key-abc', 'adc', 136, 102, $voucher_id, 0);
if (!$pushRes) {
    echo json_encode(array('msg'=>" 下发异常, {$uin}|{$voucher_id}"));
    exit;
}

//更新记录用户兑换表
$sql="update ll_duanwu_exchange_voucher set status=1 where uin=$uin and voucher_id=$voucher_id";
$obj->query($sql);
$updaRows = $obj->db->affected_rows;
if(!$updaRows){
    echo json_encode(array('code' => 51073, 'msg' => '操作频繁'));exit;
}
$result['code']=1;
$result['msg']="恭喜您，兑换成功";
echo json_encode($result);
exit;
