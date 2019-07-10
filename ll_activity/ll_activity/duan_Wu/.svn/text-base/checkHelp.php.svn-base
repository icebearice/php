<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/3
 * Time: 17:02
 * 检查是否已经助力了
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
$now=date("Y-m-d");
$obj = new Db();
$obj->use_db('read');
    $ip=getIp();
	//未注册
    $sql="select * from ll_duanwu_help_voucher_log where ip='{$ip}' and help_date='{$now}'";
	$re=$obj->query($sql);
    if (count($re)>0){
        $result['msg'] = '该ip今日已助力成功，如需继续助力请注册66，糯米数提升5倍';
        $result['code'] = 1;
        $result['data']['status']=0;
        echo json_encode($result);
        exit;
	}
		$result['msg'] = '尚未助力';
		$result['code'] = 1;
		$result['data']['status']=1;
		echo json_encode($result);
		exit;

