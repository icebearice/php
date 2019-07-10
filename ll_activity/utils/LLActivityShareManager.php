<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/error.class.php";

class LLActivityShareManager{
	private $__db;
	public function __construct(){
		$this->__db = new Db();
		$this->__db->use_db("write");
	}
	
	public function addShareTask($uin,$addCredit){
		$this->__db->query("start transaction");
		$msgs = $this->checkShare($uin);
		if($msgs===TRUE){
			return 3;
		}
		$msgs = $this->addTaskList($uin);
		if($msgs===FALSE){
			$this->__db->query("rollback");
			return 2;
		}
		$msgs = $this->addCredit($uin,$addCredit,0,"分享任务");
		if($msgs===FALSE){
			$this->__db->query("rollback");
			return 2;
		}
		$this->__db->query("commit");
		return 1;
	}

	private function checkShare($uin){
		$taskTime=date("Y-m-d");
		$sql = "select uin from ll_activity_user_task_list where uin={$uin} and task_time='{$taskTime}' limit 1;";
		$res = $this->__db->query($sql);
		if(!empty($res)){
			return TRUE;
		}
		return FALSE;
	}
	private function addTaskList($uin){
		$taskTime = date("Y-m-d");
		$addTime=date("Y-m-d H:i:s");
		$sql = "insert into ll_activity_user_task_list(uin,task_id,task_time,completed_num,first_add_time,update_time) values({$uin},8,'{$taskTime}',1,'{$addTime}','{$addTime}');";
		$res = $this->__db->query($sql);
		return $res;
	}

	private function addCredit($uin,$credit,$op,$comment){
		$addTime = date("Y-m-d H:i:s");
		$data = array(
			'uin'=>$uin,
			'credit'=>$credit,
			'op'=>$op,
			'task_id'=>8,
			'comment'=>$comment,
			'add_time'=>$addTime
		);
		$res = $this->__db->insert('ll_activity_credit_log',$data);
		if($res===FALSE){
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



