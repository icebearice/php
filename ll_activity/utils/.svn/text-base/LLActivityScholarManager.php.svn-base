<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/LLActivityBaseData.php";


class LLActivityScholarManager{
	private $__db;

	public function __construct(){
		$this->__db=new Db();
		$this->__db->use_db("read");
	}
	
	public function getScholarshipInfo($uin){
		$result = array();
		$res=$this->getTodayCredit($uin);
		if(!isset($res)){
			return array('code'=>ErrorCode::DataBase_Not_OK, 'result'=>null);
		}
		$result['credit']=$res;
		

		$res = $this->getSorted($result['credit']);
		if($res === FALSE){
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
		}
		$result['sort']=$res;

		$result['next_gap']=0;
		if($result['sort']>1){  //排名非第一，需要查找排名上一个的信息
			$nextGap = $this->get_next_gap($result['credit'],$result['sort']);
			if(!isset($nextGap)){
				return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
			}
			$result['next_gap']=$nextGap;
		}

		$userHandler = new LLUserInfoServer();
		$result['user_name']=$userHandler->getUserNameByUin($uin);
		return array('code'=>ErrorCode::OK, 'result'=>$result);
	}

	private function getSorted($today_credit){
		$sql = "select count(uin)+1 as sort from ll_activity_credit  where today_credit > {$today_credit};";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return null;
		}
		foreach($res as $v){
			return $v['sort'];
		}
		return null;
	}

	private function getTodayCredit($uin){
		$sql = "select today_credit from ll_activity_credit where uin={$uin}";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return null;
		}
		foreach($res as $v){
			return $v['today_credit'];
		}
		return 0;
	}

	private function get_next_gap($user_credit){
		$sql = "select today_credit from ll_activity_credit where today_credit>{$user_credit} order by today_credit limit 1;";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return null;
		}
		foreach($res as $v){
			return $v['today_credit']-$user_credit;
		}
		return null;
	}
	
	public function getScholarshipRecord($uin,$headIndex,$limit_num){
		$result=array();
		$info = array();
		$sql = "select a.money,b.id,b.sorted,b.add_time from ll_activity_scholarship a inner join ll_activity_scholarship_log b on a.id=b.scholarship_id  where uin = {$uin} order by b.add_time desc limit {$headIndex}, {$limit_num};";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
		}
		foreach($res as $v){
			$info['id']=$v['id'];
			$info['sort_num']=$v['sorted'];
			$info['prize_type']=Excellent_Student_Command;
			$d = new Datetime($v['add_time']);
			$info['time']=$d->modify("-1 day")->format("Y-m-d H:i:s");
			$info['prize_info']=$v['money'];

			$result[]=$info;
		}
		return array('code'=>ErrorCode::OK,'result'=>$result);
	}


}
