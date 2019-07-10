<?php
require_once dirname(dirname(__FILE__))."/include/config.php";
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityScratchUserManager.php";
FlamingoLogger::getInstance()->Logln($_REQUEST);
 
$response = array(
	'code'=>0,
	'err_msg'=>'',
	'data'=>'',
);

$uin = isset($_REQUEST['uin'])?$_REQUEST['uin']: 0;
$range =isset($_REQUEST['range'])?$_REQUEST['range']:1;
$manager=new LLActivityScratchUserManager();
$data=$manager->getUserScratchTimes($uin,$range);
$response['code']=ErrorCode::OK;
$response['data'] = $data;
echo json_encode($response);
exit;

