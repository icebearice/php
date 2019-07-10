<?php

/**
 * 用户敲金蛋
 *
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/LLActivityUserKnockEgg.php";
require_once dirname(__FILE__) . "/commonFunctions.php";

$uin = isset($_REQUEST['uin']) ? $_REQUEST['uin']:0;
FlamingoLogger::getInstance()->Logln($_REQUEST);
$response = array(
	'code'=> 0,
	'err_msg' => '',
	'data'=> '',
);

list($code,$msg) = checkActivityTime();
if($code !== 0){
	$response['code'] = $code;
	$response['err_msg'] = $msg;
        echo json_encode($response);exit;
} 


if(!$uin) {
	$response['code'] = -10;
	$response['err_msg'] = 'uin为必传';
	echo json_encode($response);
	exit();
}
$manager = new LLActivityUserKnockEggManager();
$res = $manager->knockGoldenBall($uin);
if ($res['code'] !== 0) {
	$response['code'] = $res['code'];
	$response['err_msg'] = $res['err_msg'];
	echo json_encode($response);
	exit();
}
echo json_encode($response);
exit();
