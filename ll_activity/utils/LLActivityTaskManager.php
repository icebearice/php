<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/LLActivityAggPrize.php";
class LLActivityTaskManager{
	private $__db;
	private $login_key = 'test-flamingo-login-key-abc';

	public function __construct(){
		$this->__db=new Db();
		$this->__db->use_db("write");
	}

	 public function getUserTodayCost($uin=0){
		$taskTime=date("Y-m-d");
		$sql = "select num,uin from ll_activity_user_cost where task_time='{$taskTime}' order by num desc";
		$res=$this->__db->query($sql);
		if($res===FALSE){
			return $res;
		}
		$cost=0;
		$rank = $last_rank =  0;
		$cha = '0';
		$previous_cost = 0;
        $todayData = $lastDayData = [];
		
		//今日排名
		if($res) {
			foreach($res as $k=>$v) {
				if ($v['uin'] == $uin) {
					$cost=$v['num'];
					$rank = $k+1;
					if($k>=1){
					  $previous_cost = $res[$k-1]['num'];
					}
			}
			}
		}

		if ( $rank > 1 ) {
			$cha = $previous_cost-$cost;
		}

		$cost = $this->formatNumber($cost);
		$cha = $this->formatNumber($cha);

		$userHandler=new LLUserInfoServer();
		return array('user_cost'=>$cost,'user_rank'=>$rank,'previous_cha'=>$cha+0.01);
	}


	public function getLastDayCost($uin=0,$login_key,$uuid,$productID,$platform=102,$appid = 0) {
        $taskTime = date("Y-m-d",strtotime("-1 day"));
		$sql = "select num,uin from ll_activity_user_cost where task_time='{$taskTime}' order by num desc limit 10";
		file_put_contents('/tmp/activity.log',"last day：".$sql."\r\n",FILE_APPEND);
		$res=$this->__db->query($sql);
		file_put_contents('/tmp/activity.log',"rank0：".var_export($res,true)."\r\n",FILE_APPEND);
		if($res===FALSE){
			return [0,''];
		}
		$rank = 0;
		foreach($res as $k=>$v){
			if($v['uin'] == $uin) {
				$rank = $k+1;
			}
		}
		file_put_contents('/tmp/activity.log',"rank1：".var_export($res,true)."\r\n",FILE_APPEND);

		if($rank > 0) {
		   $startTime = date('Y-m-d');
		   $endTime = date('Y-m-d H:i:s',strtotime($startTime)+24*3600);
		   $where = "a.uin= $uin  and prize_type = '代金券' and atype = 3  and a.add_time > '{$startTime}' and a.add_time < '{$endTime}'";
		   if($rank == 1) {
			   $where .= " and b.money = 298";
		   }
		   else if ($rank <=3) {
			   $where .= " and b.money = 128";
		   }
		   else {
			   $where .= " and b.money = 66";
		   }
		   $sql = "select prize_name from ll_activity_prize_log a inner join ll_activity_double_egg_prize b ".
			   "  on a.prize_id = b.id where {$where}";
		   file_put_contents('/tmp/activity.log',"last day prize：".$sql."\r\n",FILE_APPEND);
           $prize_info = $this->__db->query($sql);
		   if($prize_info) {
              return [$rank,$prize_info[0]['prize_name']];
		   }

		}
		return [0,''];
	}


	public function grantPrize($uin,$rank){
			if($rank == 1) {
				$prize_name = '298元代金券';
			}
			else if ($rank <= 3) {
				$prize_name = '128元代金券';
			}
			else if ($rank < 11) {
				$prize_name = '66元代金券';
			}

			$sql = "select * from ll_activity_double_egg_prize where prize_name = '{$prize_name}'".
				" and atype = 3 ";
			$prize_info = $this->__db->query($sql);
			//print_r($prize_info);die;

			if(!$prize_info) return false;

			$obj = new LLActivityAggPrizeManager();
			$res = $obj->giveUserPrize($uin,$prize_info[0]['id'],$this->login_key, '', 151,102, 0); 
			//var_dump($res);die;
			if (!$res) {
				return false;
			}
			return true;
	}


	public function getRankUin(){
		$t = date('Y-m-d',time()-24*3600);
		//$t = date('Y-m-d');
		$sql = "select uin from ll_activity_user_cost where task_time='{$t}' and num > 0 order by num desc limit 10";
		$rank_info = $this->__db->query($sql);
		$uin_arr = [];
		if($rank_info) {
			foreach($rank_info as $v) {
				$uin_arr[] = $v['uin'];
			}
			//$uin_arr = array_column($rank_info,'uin');
		}	

		return $uin_arr;
	}

   
	public function getUserCostList() {
		$t = date('Y-m-d');
		$sql = "select id,uin,num as cost,task_time from ll_activity_user_cost where task_time='{$t}' and num > 0 order by num desc limit 10";
		$res=$this->__db->query($sql);
                file_put_contents('tmp/activity.log',var_export($res,true),FILE_APPEND);
		if ($res) {
			$userHandler=new LLUserInfoServer();
			foreach($res as $k=>$v) {
				$userInfo = $userHandler->getUserInfoByUin($v['uin']);
				$res[$k]['user_name'] = '可爱的66玩家';
				$res[$k]['uico'] = '';

				$user_info_arr = json_decode(json_encode($userInfo),true);
				if ($v['uin'] == 3590) {
					file_put_contents('/tmp/activity.log',var_export($user_info_arr,true)."\r\n",FILE_APPEND);
				}

				file_put_contents('/tmp/activity.log',var_export($user_info_arr,true),FILE_APPEND);
				if($user_info_arr) {
					if (isset($user_info_arr['base_data']['unickname'])) {
						$v['user_name'] = $user_info_arr['base_data']['unickname'];
					}
					if(isset($user_info_arr['ext_data']['uico'])) {
						$res[$k]['uico'] = $user_info_arr['ext_data']['uico'];
					}
				}
				//file_put_contents('/tmp/activity.log',$v['uin']."\r\n",FILE_APPEND);
				//file_put_contents('/tmp/activity.log',$v['user_name']."\r\n",FILE_APPEND);
				$res[$k]['user_name'] = $this->formatUserName($v['user_name']);
				$res[$k]['cost'] = $this->formatCost($v['cost']);

			}

		}
                file_put_contents('tmp/activity.log','after:'.var_export($res,true),FILE_APPEND);
		return $res;
         
	}


	/*public function getTaskInfo($uin){
		$result = array();
		$info = array();
		$task_time = date("Y-m-d");
		$sql = "select a.task_name,a.limited_num,a.credit,a.id,b.completed_num from ll_activity_task a left join (select task_id,completed_num  from ll_activity_user_task_list where uin={$uin} and task_time='{$task_time}') b on a.id=b.task_id order by a.sorted;";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>$result);
		}
		if(empty($res)){
			return $this->getTaskType();
		}	
		foreach($res as $v){
			$info['task_name']=$v['task_name'];
			$info['credit']=$v['credit'];
			$info['limit_times']=$v['limited_num'];
			$info['task_id']=$v['id'];
			if(empty($v['completed_num'])){
				$info['done_times']=0;
			}else{
				$info['done_times']=$v['completed_num'];
			}
			$info['done_task']=0;
			if($info['done_times']==$info['limit_times']){
				$info['done_task']=1;
			}
			$result[]=$info;
		}
		return array('code'=>ErrorCode::OK,'result'=>$result);
	}

	private function getTaskType(){
		$result=array();
		$info=array();
		$sql = "select task_name,limited_num,credit,id from ll_activity_task order by sorted";
		$res = $this->__db->query($sql);
		if($res ===FALSE){
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>$result);
		}
		foreach($res as $v){
			$info['task_name']=$v['task_name'];
			$info['credit']=$v['credit'];
			$info['limit_times']=$v['limited_num'];
			$info['task_id']=$v['id'];
			$info['done_times']=0;
			$info['done_task']=0;
			if($info['done_times']===$info['limit_times']){
				$info['done_task']=1;
			}
			$result[]=$info;
		}
		return array('code'=>ErrorCode::OK, 'result'=>$result);
	}
	*/



	public function getUserTaskInfo($uin, $tid) {
	    $t = date("Y-m-d");
		$sql = "select * from ll_activity_user_task_list where uin = {$uin} and task_id = {$tid} and task_time = '{$t}'";
		$res = $this->__db->query($sql);
		if (!isset($res) || count($res) <= 0 ) {
			return null;
		}
		return $res[0];
	}

	private function formatNumber($cost){
		$cost = $cost/100;
		/*$cost = (string)$cost;
		if(strlen($cost)<3){
			$cost=sprintf("%03d",$cost);
		}
		$cost_len=strlen($cost);
		$cost = substr($cost,0,$cost_len-2).'.'.substr($cost,$cost_len-2,2);
		 */
		return $cost;

   }

	private function formatCost($cost = 0){
        //$cost = 1999999;
		if ($cost < 100 ) {
		   $cost = $cost/100;	
		}
		else if ($cost <10000) {
			$cost = intval($cost/100);
		}
		else if ($cost >= 10000) {//100
			$len = strlen($cost);
			$cost = intval($cost/100);
			$cost = (string) $cost;
			$first_a = substr($cost,0,1);
			$last_a = substr($cost,strlen($cost)-1,1);
			$xin = '';
			for($i = 0;$i<strlen($cost)-2;$i++) {
               $xin .= '*';
			}	
			$cost = $first_a.$xin.$last_a;
			//echo $cost;die;
			return $cost;
		} 
		return $cost;
   }

	private function formatUserName($name = ''){
		$len = mb_strlen($name,'UTF-8');
		$firstName = mb_substr($name,0,1,'UTF-8');
		$lastName = mb_substr($name,$len-1,1,'UTF-8');
		$name = $firstName.'***'.$lastName; 
		return $name;
   }

}
