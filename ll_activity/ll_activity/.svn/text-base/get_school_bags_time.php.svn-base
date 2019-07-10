<?php
      
require_once dirname(dirname(__FILE__)) . "/include/config.php";                                                
require_once dirname(dirname(__FILE__)) . "/include/config.inc.php";                                            
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/logger.php";
FlamingoLogger::getInstance()->Logln($_REQUEST);


$response = array(
	'code'=>0,
	'err_msg'=>'',
	'data'=>'',
);

$nowTime = date('Y-m-d H:i:s');
if($nowTime<'2018-09-01 00:00:00'){
	$response['data']['time']=1;
}else if($nowTime<'2018-09-08 00:00:00'){
	$response['data']['time']=2;
} else if($nowTime<'2018-09-09 00:00:00'){
	$response['data']['time']=3;
} else {
	$response['data']['time']=4;
}
//$response['data']['time']=2;
echo json_encode($response);
FlamingoLogger::getInstance()->Logln($response);

exit();
