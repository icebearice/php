<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";
require_once SYSDIR_UTILS . "/LLHanHuaManager.php";


$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']:0;
$sign = isset($_REQUEST['sign'])? $_REQUEST['sign']:"";
$response = array(
	'code'=>0,
	'err_msg'=>'',
	'data'=>'',
);
$data = array();
$params = ['uin'];
foreach ($_REQUEST as $key => $value){
	if(in_array($key,$params)){
		$data[$key] = trim($value);
	}
}
if (empty($_REQUEST['uin']) ||empty($_REQUEST['key']) || empty($_REQUEST['sign'])) {
	$response['code'] = 500;
    $response['err_msg'] = "参数不对";
    echo json_encode($response);
    exit();

}
$secret = isset($ACCESS_KEY[$_REQUEST['key']]['secret']) ? $ACCESS_KEY[$_REQUEST['key']]['secret']:"";
$csign = create_verify($data,$secret); 
if ($uin <= 0 || count($sign)<=0 || $sign != $csign) {
	$response['code'] = 500;
    $response['err_msg'] = "参数不对  ". $csign;
    echo json_encode($response);
    exit();
}
$manager = new LLHanHuaManager();
$voucherInfo = $manager->sendUserVoucher($uin);
if (isset($voucherInfo)) {
    $response['data']['voucher_get_time'] = $voucherInfo['add_time'];
    $response['data']['is_get_voucher'] = true; 
}else {
    $response['data']['is_get_voucher'] = false; 
}
echo json_encode($response);
exit();

