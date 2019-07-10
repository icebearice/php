<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/error.class.php";

class LLActivityScratchHomePageManager
{
	private $__db;
	public function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("read");
	}
    
	public function verifyActivityTime(){
		$t=time();
		//$t=1538236800;
		$start=1538236800;
		$end=1538928000;
		$result;
		if($t<$start){
			$result=1;
		}else if($t>=end) {
			$result=2;
		}
		$result=3;
	   return $result;
	}
	public function getHomePage($uin,$range) {
		$result = array();
		$t=Date("Y-m-d");
		if ($uin > 0) {
            $sql = "select scratch_times,prize_total_times  from ll_activity_user_scratch  where uin = {$uin} and scratch_range={$range}";
			$data = $this->__db->query($sql);
			$result['scratch_times'] = isset($data[0]['scratch_times']) ? $data[0]['scratch_times']:0;	
			$result['prize_times'] = isset($data[0]['prize_total_times'])? $data[0]['prize_total_times'] :0;	
		}
		$result['taskList'] = $this->getUserTaskInfo($uin);
		$result['userPrizeList'] = $this->getOtherUserPrizeInfo($range);
		return array('code'=>ErrorCode::OK, 'result'=>$result);
	}

	public function getOtherUserPrizeInfo($range) {
		$result = array();
		$sql = "select log.id,uin,prize_name,prize_status,log.add_time from ll_activity_user_scratch_log log,ll_activity_scratch_prize p where log.prize_id=p.id and p.scratch_range={$range} order by log.add_time desc limit 30";
		$data = $this->__db->query($sql);
		if (!isset($data) || count($data) <= 0) {
			return array('code'=>ErrorCode::OK, 'result'=>$result);
		}
		$userHandler = new LLUserInfoServer();
		foreach ($data as $k=>$v) {
			$info = array();
			$info['id'] = $v['id'];
			$info['prize_name'] = $v['prize_name'];
			$info['time'] = strtotime($v['add_time']);
			$info['prize_status'] = $v['prize_status'];
			$user_name=$userHandler->getUserNameByUin($v['uin']);
			$info['user_name']=$this->getCoverName($user_name);
			$result[] = $info;
		}
		return $result;


	}

	public function isUserFinishAllTask($uin) {
		$tasks = $this->getUserTaskInfo($uin);
		foreach ($tasks as $k=>$v) {
			if ($v['task_status'] == 0) {
				return false;
			}
		}
		return true;
	}
	private function getCoverName($name){
		$strlen = mb_strlen($name, 'utf-8');
		$firstStr = mb_substr($name, 0, 1, 'utf-8');
		$lastStr  = mb_substr($name, -1, 1, 'utf-8');
		return $strlen == 2 ? $name : $firstStr .'*'.$lastStr;
	}

	public function getUserTaskInfo($uin) {
		$task_time = date("Y-m-d");
		$sql = "select a.id, a.task_type, a.task_name, a.day_times, a.task_difficulty,b.task_status from ll_activity_scratch_task a left join (select task_id,task_status from ll_activity_user_scratch_task_list where uin={$uin} and task_time='{$task_time}') b on a.id=b.task_id order by a.sorted_num;";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return null;
		}
		foreach($res as $v){
			$info['task_id']=$v['id'];
			$info['task_status']= isset($v['task_status']) ? $v['task_status'] : 0;
			$info['task_name']=$v['task_name'];
			$info['task_type']=$v['task_type'];
			$info['day_times']= $v['day_times'];
			$info['task_difficulty']= $v['task_difficulty'];
			$result[]=$info;
		}
		return $result;
	}
	private function getPrizeInfo($range) {
		$sql = "select id, prize_name, prize_desc, prize_property, money, money_id, prize_amount, icon from ll_activity_scratch_prize a where  scratch_range={$range} order by a.sorted_num desc";
		$data = $this->__db->query($sql);
		if (!isset($data) || count($data) <= 0) {
			return null;
		}
		$result = array();
		foreach ($data as $k=>$v) {
			$info = array();
			$info['prize_id'] = $v['id'];
			$info['prize_name'] = $v['prize_name'];
			$info['prize_desc'] = $v['prize_desc'];
			$info['prize_property'] = $v['prize_property'];
			$info['money'] = $v['money'];
			$info['prize_amount'] = $v['prize_amount'];
			$info['icon'] = $v['icon'];
			$result[] = $info;
		} 
		return $result;
	}
}
