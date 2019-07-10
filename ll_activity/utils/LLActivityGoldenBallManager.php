<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/voucherServer.class.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS."/LLActivityGoldenBollPrize.php";
require_once SYSDIR_UTILS."/LLActivityAggPrize.php";


class LLActivityGoldenBallManager {
	private $__db;
	private $__golden_ball_task = array(1,2,3);
	public function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("write");
	}

	public function getAllGoldenBallTask() {
		$str = implode(',',$this->__golden_ball_task);
		$sql =  "select * from ll_activity_task where id in ({$str})";
		$data = $this->__db->query($sql);
		return $data;
	}

	public function getUserTask($uin,$login_key, $uuid, $productID, $platform, $appid) {
		$t = date("Y-m-d");
		$t_str = implode(',',$this->__golden_ball_task);
		$sql = "select a.*,b.task_name from ll_activity_user_task_list a left join ll_activity_task b on a.task_id = b.id  where a.uin = {$uin} and a.task_time = '{$t}' and task_id in ({$t_str}) order by a.id asc";
		$data = $this->__db->query($sql);

		if($data) { //任务的状态
			foreach($data as &$v) {
				$obj = new LLActivityGoldenBollPrizeManager();
				$prize_info = $obj->getPrizeByTask($v['task_id']);
				$v['prize_type'] = '';
				$v['money'] = 0;
				if ($prize_info) {
					$v['prize_type'] = $prize_info[0]['prize_type'];
					if ($v['prize_type'] == '代金券') {
						$v['prize_type'] = '元'.$v['prize_type'];
					}
					else if ($v['prize_type'] == '成长值')  {
						$v['prize_type'] = '点'.$v['prize_type'];
					}
					$v['money'] = $obj->getPrizeMoney($prize_info[0]['prize_name']);
				}
				if($v['task_id'] == 3) {
                                        $start_time = strtotime($t);
                                        $end_time = strtotime($t)+24*3600;
					$sql = "select count(*) as num from ll_activity_user_knock_egg_log where uin = {$uin} and add_time >={$start_time} and add_time < {$end_time} ";
					$num_info = $this->__db->query($sql);
					$v['share_num'] = 0;
					if($num_info){
						$v['share_num'] = $num_info[0]['num'];
					}

				}
				if ($v['task_id'] == 2 && $v['status'] == 1 && $v['completed_num'] == 0) {
					$sql = "select uin,num from ll_activity_user_cost where uin = {$v['uin']} and task_time='{$t}'
						";                                                                                                                

						$this->__db->use_db('read');                                                                  

					$cost_info = $this->__db->query($sql);                                                        
					if ($cost_info) {                                                                             
						if ($cost_info[0]['num'] >= 5) {                                                       
							$res = $this->setUseTaskStatus($v['id'],2);                                           
							if ($res){                                                                            
								$v['status'] = 2;                                                                  
							}                                                                                     

							//发放奖励
							$obj = new LLActivityAggPrizeManager();
							$res = $obj->giveUserPrize($uin,$prize_info[0]['id'],$login_key,$uuid,$productID,$platform,$appid,$v['task_id']); 
							if ($res) {
								$sres = $this->setUseTaskStatus($v['id'],3);                                           
								if ($sres) {
									$v['status'] = 3;
								}

							}
                                              }
					}    
				}

			}

		}

		//echo '<pre>';print_r($data);
		return $data;
	}


	public function setUseTaskStatus($id,$status){
		$up_data['completed_num'] = 1;
		$up_data['status'] = $status;
		$this->__db->use_db('write');
		$res = $this->__db->update('ll_activity_user_task_list',$up_data,['id'=>$id]);
		return $res;

	}

	public function getGoldenBallTask($uin){
		$all_task = $this->getAllGoldenBallTask();
		if (!$all_task) return null;
		shuffle($all_task);
		$t = date("Y-m-d");
		$sql = "select * from ll_activity_user_task_list where uin = {$uin} and task_time = '{$t}'";
		$user_task = $this->__db->query($sql);
		$no_selected_task = $selected_task =  [];

		if ($user_task) {
			foreach($user_task as $v) {
				$selected_task[] = $v['task_id'];
			}
			//$selected_task = array_column($user_task,'task_id'); 
			foreach($all_task as $k=>$v) {
				if (!in_array($v['id'],$selected_task)) {
					$no_selected_task[] = $v;
				}
			}

		}
		else {
			$no_selected_task = $all_task;
		}

		$no_selected_num = count($no_selected_task);
		if ($no_selected_num == 0) return null;
		$rand_num = rand(0,$no_selected_num-1);
		$task = $no_selected_task[$rand_num]; 

		if ($task) {
			$insert = array();
			$insert['uin'] = $uin;
			$insert['task_id'] = $task['id'];
			$insert['task_time'] = date("Y-m-d");
			$insert['completed_num'] = 0;
			$insert['status'] = 1;
			$insert['ip'] = getIp();
			$insert['first_add_time'] = date("Y-m-d H:i:s");
			$insert['update_time'] = date("Y-m-d H:i:s");
			$this->__db->use_db('write');
			$res = $this->__db->insert("ll_activity_user_task_list", $insert);
			file_put_contents('/tmp/activity.log',$this->__db->get_sql(),FILE_APPEND);
			if (!$res) null;
			$prize_type = '';
			$money = 0;

			$obj = new LLActivityGoldenBollPrizeManager();
			$prize_info = $obj->getPrizeByTask($task['id']);
			if ($prize_info) {
				$prize_type = $prize_info[0]['prize_type'];
				if ($prize_type == '代金券') {
					$prize_type = '元'.$prize_type;
				}
				else if ($prize_type == '成长值')  {
					$prize_type = '点'.$prize_type;
				}
				$money = $obj->getPrizeMoney($prize_info[0]['prize_name']);
			}
			return array(
					'task_id' =>$task['id'],
					'status' =>1,
					'task_name'=>$task['task_name'],
					'prize_type' => $prize_type,
					'money' => $money
				    );
		}

	}



	/*public function getGoldenBallTask($uin) {
	  $allTask = $this->getAllGoldenBallTask();
	  $userTask = $this->getUserTask($uin);
	  shuffle($allTask);
	  $task = null;
	  foreach ($allTask as $k=>$v) {
	  $isIn = false;
	  foreach($userTask as $kk=>$vv) {
	  if ($v['id'] == $vv['task_id']) {
	  $isIn = true;
	  break;
	  }
	  }
	  if ($isIn == false) {
	  $task = $v;
	  break;
	  }
	  }
	  if ($task) {
	  $insert = array();
	  $insert['uin'] = $uin;
	  $insert['task_id'] = $task['id'];
	  $insert['task_time'] = date("Y-m-d");
	  $insert['completed_num'] = 0;
	  $insert['status'] = 1;
	  $insert['first_add_time'] = date("Y-m-d H:i:s");
	  $insert['update_time'] = date("Y-m-d H:i:s");
	  $this->__db->insert("ll_activity_user_task_list", $insert);
	  return array(
	  'task_id' =>$task['id'];
	  'task_name'=>$task['task_name'];
	  );
	  }
	  return null;
	  }
	 */
}
