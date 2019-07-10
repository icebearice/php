<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/14
 * Time: 18:20
 * 获取代金券列表
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
	'count_of_nuo_mi'=>0,
    'data' => array(),
);

//是否为活动时间
$time = isActivityTime($startTime, $endTime);

if (!$time['status']) {
    $result['code']=517;
    $result['msg'] = '活动时间已结束';
    echo json_encode($result);exit;
}



// 是否登录
$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin'] : 0;
$login_key = isset($_REQUEST['login_Key']) ? $_REQUEST['login_Key'] : '';
$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : '';
$productID = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 136;
$platform = isset($_REQUEST['platformType']) ? $_REQUEST['platformType'] : 102;
$appid = isset($_REQUEST['appID']) ? $_REQUEST['appID'] : 0;
$now=date("Y-m-d");
// 已登录
$obj = new Db();
$obj->use_db('read');
$result['data']=array();
foreach ($helpArr as $k => $v){
    $voucherArr=array();
    $voucher_id=$v['voucher_id'];
    $id=$v['id'];
    $name=$v['name'];
    $image=$v['image'];
	$spend_nuo_mi=$v['spend_nuo_mi'];
	$num=$v['num'];
	$condition=$v['condition'];
    $sql="select total_num  from ll_duanwu_total_voucher_daily_num  where voucher_id=$voucher_id and activity_date ='{$now}'";
	$data=$obj->query($sql);
    $total_num=0;
    if (!empty($data)){
        $total_num=$data[0]['total_num'];
        $total_num=intval($total_num);
    }
	$status=0;
	if($uin||is_numeric($uin)){
		$logSql="select * from ll_duanwu_exchange_voucher  where uin=$uin and voucher_id=$voucher_id" ;
		$exchange=$obj->query($logSql);
		if(!empty($exchange)){
			$status=1;
		} 		
	}
    $voucherArr['id']=$id;
    $voucherArr['name']=$name;
    $voucherArr['image']=$image;
	$voucherArr['status']=$status;
    $voucherArr['spend_nuo_mi']=$spend_nuo_mi;
	$voucherArr['total_num']=$total_num;
	$voucherArr['num']=$num;
	$voucherArr['condition']=$condition;
    $result['data'][]=$voucherArr;
}
$auth = new LLUserAuthServer();
if (!$auth->checkUserLogin($productID, $uuid, $platform, $uin, $login_key, $appid)) {
    $result['code'] = ErrorCode::User_Not_Login;
    $result['msg'] = "未登录";
    echo json_encode($result);
    exit();
}
//登陆
$sql="select count_of_nuo_mi from ll_duanwu_help_voucher_select where uin=$uin and select_date='{$now}'";
$data=$obj->query($sql);
if(!empty($data)){
	$result['count_of_nuo_mi']=$data[0]['count_of_nuo_mi'];
}
$result['msg']='获取代金券列表成功';
$result['code']=1;
echo json_encode($result);
exit;
