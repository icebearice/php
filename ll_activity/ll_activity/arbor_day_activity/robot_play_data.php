<?php
require_once dirname(dirname(__DIR__))."/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS ."/logger.php";
require_once SYSDIR_UTILS ."/LLActivityBaseData.php";
require_once SYSDIR_UTILS ."/arbor_day_activity/robotData.php";
require_once SYSDIR_UTILS ."/Ding.class.php";

$response = array(
	'code'=> 0,
	'err_msg' => '',
	'data'=> '',
);

//$url = "https://oapi.dingtalk.com/robot/send?access_token=7a91b90678430df12ea975342bd4a004c91da018ac568925ac6979be9e562361";
$url = "https://oapi.dingtalk.com/robot/send?access_token=5f574350a90dc267a39858bdfbfc4fd5aa11e4230d7bcdd83e73f35c068390d4";
$ding = new Ding($url);
$data = "";
$res = getData();
foreach ($res as $key => $value) {
	$data.= $key.": ".$value."  \r\n";	
}
$ding->send_markdown("旺旺",$data);
