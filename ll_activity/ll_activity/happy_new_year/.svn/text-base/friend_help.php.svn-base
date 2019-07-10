<?php
/*************************************************************************
 * File Name: friend_help.php
 * Author: lvchao.yan
 * Created Time: 2018.12.25
 * Desc: 好友助力
 *************************************************************************/

require_once dirname(dirname(__DIR__)). "/include/config.php";
require_once dirname(dirname(__DIR__)). "/include/config.inc.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";
require_once SYSDIR_UTILS . "/REDIS.php";

checkActivityDate();

$response = array(
	"code" => 0,
	"err_msg" => '',
	"data" => '',
);

$__cache = new myRedis();
$__cache->use_redis("read");

$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']:0;
$key = $uin."_share_ip";
$share_ip = $__cache->redis->get($key);  //读取分享者的IP
$help_ip = getIp();
if ($share_ip == $help_ip) {  //不能给自己助力                                                                                                           
	$response['code'] = ErrorCode::Can_Not_Help_Yourself;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}
$res = friendHelp($uin);
if (!$res) {  //助力失败
	$response['code'] = ErrorCode::This_Ip_Has_Helped;
	$response['err_msg'] = ErrorCode::getTaskError($response['code']);
	echo json_encode($response);
	exit();
}

echo json_encode($response);
exit();
