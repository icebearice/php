<?php
//$_SERVER['RUN_MODE'] = 'staging';
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . '/include/config.inc.php';
require_once SYSDIR_UTILS . "/DB.php";

main();
function main(){
	$message = array();
	$db = new Db();
	$db->use_db('write');
	foreach($message as $k=>$v){
		$insert=array(
			'uin'=>rand(1000,10000) ,
			'message_type'=>0,
			'sorted'=>0,
			'add_time'=>date('Y-m-d H:i:s'),
			'message'=>$v,
			'credit'=>0,
			'status'=>0
		);
		$db->insert('ll_activity_message',$insert);
	}
}


