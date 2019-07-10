<?php
//$_SERVER['RUN_MODE'] = 'staging';
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once dirname(dirname(__FILE__)) . '/include/config.inc.php';
require_once SYSDIR_UTILS . "/DB.php";

main();
function main(){
	$uins = array(161=>array('sorted'=>1,'credit'=>20),
				1=>array('sorted'=>2,'credit'=>15),
				200=>array('sorted'=>3,'credit'=>10),
				1202=>array('sorted'=>4,'credit'=>5)
	);
	$msg = new AddMessageTop();
	$res = $msg->run($uins);
	var_dump($res);
}
class AddMessageTop{

	private $__db;
	public function __construct(){
		$this->__db = new Db();
		$this->__db->use_db("write");
	}

	public function run($uins){
		$this->__db->query('start transaction');
		foreach($uins as $k=>$v){
			$res = $this->updateMessage($v['sorted'],$k,$v['credit']);
			if($res === FALSE){
				$this->__db->query('rollback');
				return FALSE;
			}
			$res = $this->addCredit($k,$v['credit'],0,'置顶加精');
			if($res === FALSE){
				$this->__db->query('rollback');
				return FALSE;
			}
		}
		$this->__db->query('commit');
		return TRUE;
	}


	private function updateMessage($sorted,$uin,$credit){
		$sql = "update ll_activity_message set credit = {$credit},message_type=1,sorted={$sorted},status=1 where uin={$uin};";
		$res = $this->__db->query($sql);
		return $res;
	}

	private function addCredit($uin,$credit,$op,$comment){
		$addTime = date("Y-m-d H:i:s");
		$data = array(
			'uin'=>$uin,
			'credit'=>$credit,
			'op'=>$op,
			'task_id'=>0,
			'comment'=>$comment,
			'add_time'=>$addTime
		);
		$result = $this->__db->insert('ll_activity_credit_log',$data);
		if($result === FALSE){
			return FALSE;
		}

		$sql = "insert into ll_activity_credit (uin,credit,today_credit,update_time) values({$uin},{$credit},{$credit},'{$addTime}') ON DUPLICATE KEY "
		       ."update  credit = credit + {$credit} , today_credit = today_credit+{$credit} , update_time ='{$addTime}';";
		$result = $this->__db->query($sql);
		if($result === FALSE){
			return  FALSE;
		}
		return TRUE;
	}
}
