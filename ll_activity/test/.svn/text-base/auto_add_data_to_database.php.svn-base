<?php 
$_SERVER['RUN_MODE'] = 'staging';
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/DB.php";

main();
function main(){
//	addCredit();
	addScholarship();
}

function addScholarship(){
	$db=new Db();
	$db->use_db("write");
	date('Y-m-d',strtotime('-1 day'));
	$scholar = array(
		'学神'=>array('money'=>'100代金券','money_id'=>442,'completed_num'=>0,'limited_num'=>5,'range'=>'1,5'),
		'学圣'=>array('money'=>'60代金券','money_id'=>444,'completed_num'=>0,'limited_num'=>15,'range'=>'6,20'),
		'学霸'=>array('money'=>'30代金券','money_id'=>446,'completed_num'=>0,'limited_num'=>30,'range'=>'21,50'),
		'学痞'=>array('money'=>'10代金券','money_id'=>448,'completed_num'=>0,'limited_num'=>50,'range'=>'51,100'),
		'学民'=>array('money'=>'5代金券', 'money_id'=>450,'completed_num'=>0,'limited_num'=>200,'range'=>'101,300')
	);
	$count = 7;
	$index = 3;
	while($count--){
		$insert['task_time']=date('Y-m-d',strtotime('+'.$index.' day'));
		$index++;
		$insert['add_time']=date('Y-m-d H:i:s');
		foreach($scholar as $k=>$vf){
			$insert['scholarship']=$k;
			foreach($vf as $kk=>$vv){
				$insert[$kk]=$vv;
			}
			$db->insert('ll_activity_scholarship',$insert);
		}
	}
}
function addCredit(){
	$count = 500;
	$db=new Db();
	$db->use_db("write");

	while($count--){
		$insert['today_credit']=rand(0,1000);
		$insert['credit']=$insert['today_credit']+rand(0,1000);
		$insert['uin']=rand(1201,4000);
		$db->insert('ll_activity_credit',$insert);
	}	
}
