<?php

/**
 * 用户敲金蛋
 */

require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS."/XXRequestBase.php";
require_once SYSDIR_UTILS."/LLActivityAggPrize.php";

class LLActivityUserKnockEggManager {

	private $__db;
	private $table;
	private $task_id = 3;
	private $login_key = 'test-flamingo-login-key-abc';
	public function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("write");
		$this->table = 'll_activity_user_knock_egg_log';
		$this->user_task_table = 'll_activity_user_task_list';
	}

	public function getKnockPower($uin,$ip){
		  $sql = "select * from {$this->user_task_table} where ip = '{$ip}'";
		  $join_info = $this->__db->query($sql);
		  if ($join_info) {
                      if ($join_info[0]['uin'] == $uin) {
		          return ErrorCode::Can_Not_self_Open;//不能给自己开宝箱
                      }
                      else {
		          return ErrorCode::You_Has_Joined;
                      }
		  }
		  $ksql = "select * from {$this->table} where ip = '{$ip}'";
		  $knock_egg_info = $this->__db->query($ksql);
		  if($knock_egg_info) {
		     return ErrorCode::This_Ip_knocked;//该ip已经砸了
		  }
		 	
		return 0;
	}

	public function knockGoldenBall($uin) {
		$ip = getIp();
		$code  = $this->getKnockPower($uin,$ip);
		if ($code !== 0) {
			return ['code'=>$code,'err_msg'=>ErrorCode::getTaskError($code)] ;
		}

		$in_data['uin'] = $uin;
		$in_data['ip'] = getIp();
		$in_data['add_time'] = time();

                $this->__db->use_db('write');
		$res = $this->__db->insert($this->table,$in_data);
		if (!$res) {
			return ['code'=>400,'err_msg'=>''] ;
		}
                $start_time = strtotime(date('Y-m-d'));
                $end_time = strtotime(date('Y-m-d'))+24*3600;
		$sql = "select count(id) as num from {$this->table} where uin = {$uin} and add_time >={$start_time} and ass_time < {$end_time}";
                $this->__db->use_db('read');
		$total_info = $this->__db->query($sql);
		if ($total_info) {
			if ($total_info[0]['num'] == 5) {
				//设置任务已完成
				$t = date('Y-m-d');
				$up_data['completed_num'] = 1;
				$up_data['status'] = 2;

                                $this->__db->use_db('write');
				$this->__db->update('ll_activity_user_task_list',$up_data,['uin'=>$uin,'task_id'=>$this->task_id,'task_time'=>$t]); 
                                file_put_contents('tmp/activity.log','好友分享sql:'.$this->__db->get_sql()."\r\n",FILE_APPEND);

				//发放奖励
				$obj = new LLActivityAggPrizeManager();
				$prize_info = $this->getPrizeByTask(Share_Task_ID);
				if (!$prize_info) {
					return [400,'奖励已发放完毕'];
				}

				$res = $obj->giveUserPrize($uin,$prize_info[0]['id'],$this->login_key,'',151,102,0,Share_Task_ID);				 
				//file_put_contents('/tmp/activity.log',$uin,FILE_APPEND);
				file_put_contents('/tmp/activity.log',var_export($res,true),FILE_APPEND);
				if(!$res) {
					return [400,'err_msg'=>'奖励发放失败'];
				}
				$up_data1['status'] = 3;
                                $this->__db->use_db('write');
				$this->__db->update('ll_activity_user_task_list',$up_data1,['uin'=>$uin,'task_id'=>$this->task_id,'task_time'=>$t]); 

			}
		}
		return ['code'=>0,'err_msg'=>'']; 
	}



	public function getPrizeByTask($task_id){
		$prize_info = [];
		if ($task_id == 3) {
			$sql = "select * from ll_activity_double_egg_prize where prize_name = '1元代金券' and ".
				" completed_num < limited_num and atype = 2";
                        $this->__db->use_db('read');

			$prize_info = $this->__db->query($sql);
		}
		return $prize_info;
	}


	public function logJoinUser($uin){
		$ip = getIp();
		$sql = "select * from ll_activity_join_user_list where type = 1 and uin = {$uin}";
		$join_info = $this->__db->query($sql);
		if (!$join_info) {
			$in_data['uin'] = $uin;
			$in_data['ip'] = ip2long($ip);
			$in_data['add_time'] = time();
			$res = $this->__db->insert('ll_activity_join_user_list',$in_data);
		}
	}


}
