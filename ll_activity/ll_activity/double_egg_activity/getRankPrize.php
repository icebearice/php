<?php

/**
 * 判断用户是否可以砸金蛋
 */

require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/LLActivityTaskManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once dirname(__FILE__) . "/commonFunctions.php";

//ini_set("display_errors", "On");
//error_reporting(E_ALL);

/*list($code,$msg) = checkActivityTime();
if($code !== 0){
	$response['code'] = $code;
	$response['err_msg'] = $smg;
	echo json_encode($response);
	exit;
} 
*/

$manager = new LLActivityTaskManager();
$uin_arr = $manager->getRankUin();
//print_r($uin_arr);die;
if ($uin_arr) {
	foreach($uin_arr as $k=>$v){
       $res = $manager->grantPrize($v,$k+1);		
	   if(!$res) {
		   file_put_contents('/tmp/ll_double_activity_rank_prize.log','补发2号'." uin:{$v}发放奖励失败\r\n",FILE_APPEND);
	   }
	   else {
		   file_put_contents('/tmp/ll_double_activity_rank_prize.log','补发2号'." uin:{$v}发放奖励成功\r\n",FILE_APPEND);
	   }
	}
}

