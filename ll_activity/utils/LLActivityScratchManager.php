<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/voucherServer.class.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/LLIconManager.class.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS . "/LLActivityScratchHomePageManager.php";



class LLActivityScratchManager {
	private $__db;
	private $__homeManager;
	public function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("read");
		$this->__homeManager = new LLActivityScratchHomePageManager();
	}

	public function verifyActivityTime(){
		$t=time();
		//$t=1538236800;
		$start=1538236800;
		$end=1538928000;
		if($t<$start){
			return 1;
		}else if($t>=$end){
			return 2; 
		}
		return 3;
	}

	public function getUserScratchTimes($uin,$range) {
		$sql = "select scratch_times,prize_total_times from ll_activity_user_scratch  where uin = {$uin}";
		$data = $this->__db->query($sql);
		if ((!isset($data) || count($data) <= 0) && $this->verifyActivityTime() == 3)  {
			$insert=array();
			$insert['uin']=$uin;
			$insert['scratch_times']=1;
			$insert['prize_total_times']=0;
			$insert['scratch_range']=0;
			$time=date("Y-m-d H:i:s");
			$insert['add_time']=$time;
			$this->__db->use_db("write");
			if (false == $this->__db->insert('ll_activity_user_scratch',$insert)) {
				FlamingoLogger::getInstance()->Logln('插入用户抽奖次数关系表失败 '.'uin '.$uin);
				$this->__db->query("rollback");
				return array('code'=>ErrorCode::DataBase_Not_OK, 'result'=>null);
			}
			$this->__db->use_db("read");
			return array('scratch_times'=>1, 'prize_total_times'=>0);
		}

		if(!isset($data)||count($data)<=0||$this->verifyActivityTime()!=3){
	         return array('scratch_times'=>0,'prize_total_times'=>0);	
		}
		return $data[0];
	}

	public function getUserPrizeList($uin,$range) {
		$sql = "select log.id,uin, prize_id,prize_status,p.prize_name,icon,log.add_time FROM ll_activity_user_scratch_log log,ll_activity_scratch_prize p WHERE uin = {$uin} AND log.prize_id=p.id AND p.scratch_range={$range} ORDER By log.add_time desc";
		$data = $this->__db->query($sql);
		$result=array();
		if (!isset($data) || count($data) <= 0) {
			return array('code'=>ErrorCode::OK, 'result'=>$result);
		}
		foreach ($data as $k=>$v) {
			$info = array();
			$info['id'] = $v['id'];
			$info['prize_name'] = $v['prize_name'];
			$info['icon']=$v['icon'];
			$info['time']=date('Y-m-d',strtoTime($v['add_time']));
			$info['prize_status'] = $v['prize_status'];
			$result[] = $info;
		}
		return array('code'=>ErrorCode::OK, 'result'=>$result);

	}

	public function getUserScratch($uin, $loginKey, $uuid, $productID, $platform, $appid = 0,$range) {
		$userHandler = new LLUserInfoServer();
		$userInfo = $userHandler->getUserInfoByUin($uin);
		if(!isset($userInfo)){
			return array('code'=>ErrorCode::User_Not_Phone, 'result'=> null);
		}
		$userPhone = $userInfo->getBase_data()->getUphone();
		if(empty($userPhone)){
			return array('code'=>ErrorCode::User_Not_Phone, 'result'=> null);
		}
		$this->__db->use_db("write");
		$this->__db->query("start transaction");
		$times = $this->getUserScratchTimes($uin,$range);
		if ($times['scratch_times'] <= 0) {
			FlamingoLogger::getInstance()->Logln('用户抽奖次数不足: '.'uin '.$uin.' loginkey '.$loginKey.json_encode($times));
			$this->__db->query("rollback");
			if ($this->__homeManager->isUserFinishAllTask($uin)) {
				return array('code'=>ErrorCode::User_Stratch_Times_Not_Enough, 'result'=>null);
			}else {
				return array('code'=>ErrorCode::User_Stratch_Times_Not_Enough_And_Not_Finish_AllTask, 'result'=>null);
			}
		}
		$lottery =  $this->getLotteryInfo($uin,$loginKey,$range);
		$money_id=$lottery['money_id'];
		$prize_id = $lottery['id'];
		// 修改奖品剩余个数
		$sql = "update ll_activity_scratch_prize set allowance = allowance - 1 where id = {$prize_id} and scratch_range={$range}";

		if ($this->__db->query($sql) == false) {
			FlamingoLogger::getInstance()->Logln('修改商品剩余个数失败: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
			$this->__db->query("rollback");
			return array('code'=>ErrorCode::DataBase_Not_OK, 'result'=>null);
		}

		// 插入用户奖品关系
		$insert['uin'] = $uin; 
		$insert['prize_id'] = $prize_id;
		$insert['add_time'] = date("Y-m-d H:i:s");
		$prize_status = 0;
		// 发送代金券
		if ($lottery['prize_property'] == 1 || $lottery['prize_property'] == 2) {
			$voucherServer = new LLVoucherServer();
			if (true == $voucherServer->sendVoucher($uin, $loginKey, $uuid, $productID, $platform, $lottery['money_id'],$appid)) {
				FlamingoLogger::getInstance()->Logln('代金券发放成功: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
				$prize_status = 1;
			}else {
				FlamingoLogger::getInstance()->Logln('代金券发放失败: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
				$prize_status = 2;
			}
			// 发送6币
		}else if ($lottery['prize_property'] == 3) {
			$platformCoinServer = new LLIconManager();
			if (true == $platformCoinServer->sendPlatformCoin($uin, $lottery['money'],'中秋活动刮奖赠送平台币')) {
				FlamingoLogger::getInstance()->Logln('平台币发放成功: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
				$prize_status = 1;
			}else {
				FlamingoLogger::getInstance()->Logln('平台币发放失败: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
				$prize_status = 2;
			}
		}
		$insert['prize_status'] = $prize_status;
		if (false == $this->__db->insert('ll_activity_user_scratch_log',$insert)) {
			FlamingoLogger::getInstance()->Logln('插入用户中奖产品关系失败: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
			$this->__db->query("rollback");
			return array('code'=>ErrorCode::DataBase_Not_OK, 'result'=>null);
		}
		$sql = "update ll_activity_user_scratch set scratch_times = scratch_times - 1, prize_total_times = prize_total_times + 1 where uin = {$uin}";
		if (false == $this->__db->query($sql)) {
			FlamingoLogger::getInstance()->Logln('更新用户抽奖次数失败: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
			$this->__db->query("rollback");
			return array('code'=>ErrorCode::DataBase_Not_OK, 'result'=>null);
		}
		$this->__db->query("commit");
		$icon=$lottery['icon'];
		$result = array();
		$result['stratch_times'] = $times['scratch_times']  - 1;
		$result['prize_total_times'] = $times['prize_total_times']  + 1;
		$result['prize_property'] = $lottery['prize_property'];
		$result['prize_name'] = $lottery['prize_name'];
		$result['prize_status'] = $prize_status;
		$result['icon']=$icon;
		FlamingoLogger::getInstance()->Logln('用户抽奖成功: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
		return array('code' => ErrorCode::OK, 'result' => $result);
	}


	private function getLotteryInfo($uin,$loginKey,$range) {
		$cost=0;
		$start = date("Y-m-d");
		$end =date("Y-m-d",strtotime("+1 day"));
		$sql="select num as cost from ll_activity_user_cost where uin={$uin} and update_time>='{$start}' and update_time<'{$end}'";
		$data=$this->__db->query($sql);
		if(isset($data)&&count($data)>0){
			foreach($data  as $v){
				$cost+=$v['cost'];	  
			}
			$cost=$cost/100;
			if($cost>=0&&$cost<=5){
				$prize_rank=1;
			}else if($cost>5&&$cost<30){
				$prize_rank=2;
			}else if($cost>=30&&$cost<50){
				$prize_rank=3;
			}else if($cost>=50&&$cost<100){
				$prize_rank=4;			
			}else if($cost>=100&&$cost<300){
				$prize_rank=5;
			}else if($cost>=300&&$cost<800){
				$prize_rank=6;
			}else if($cost>=800&&$cost<1000){
                $prize_rank=7;			
			}else if($cost>=1000){
                $prize_rank=8;			
			}
		}else{
			//查询不到对应用户当天消费记录
			$prize_rank=1;  
		}
		$lottery=null;
		$isGetBigPrize=0;
		while ($prize_rank > 0) {
			if ($prize_rank>=3) {
				FlamingoLogger::getInstance()->Logln('准备查询用户是否中过大奖: '.'uin '.$uin.' loginkey '.$loginKey, "cost ".$cost);
				$now = date("Y-m-d");
				$sql = "select * from ll_activity_user_scratch_log a left join ll_activity_scratch_prize b on a.prize_id = b.id where a.uin = {$uin} and  b.prize_rank = {$prize_rank} and  b.scratch_range={$range} and a.add_time > '{$now}'";
				$data = $this->__db->query($sql);
				if (isset($data) && count($data) >0) {
					FlamingoLogger::getInstance()->Logln('抽过大奖，用户到小奖范围随机抽: '.'uin '.$uin.' loginkey '.$loginKey, "cost ".$cost, $prize_rank);
					$isGetBigPrize=1;
				}
				if(!$isGetBigPrize){
					$sql="select * from ll_activity_scratch_prize where prize_rank ={$prize_rank} and allowance >0 and scratch_times_day >0 and scratch_range={$range} order by rand() desc";
					$data=$this->__db->query($sql);
					if(isset($data)&&count($data)>0){
						foreach($data as $v){
							$sql="update ll_activity_scratch_prize set scratch_times_day=scratch_times_day-1 where id={$v['id']} ";
							if(false==$this->__db->query($sql)){
								FlamingoLogger::getInstance()->Logln('更新奖品当天剩余可抽中次数失败:','uin'.$uin.'loginKey'.$loginKey);
							    $this->__db->query("rollback");	
								return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
							
							}
							return $v;
                    						
						}	
					
					}else{
				         $prize_rank--;
					     continue;	 
					}  			
			       	
				}	
				
			}
			//小奖抽奖
			$sql="select * from ll_activity_scratch_prize where prize_rank = {$prize_rank} and allowance > 0 and scratch_times_day > 0 and scratch_range={$range} and prize_property in (2,3) order by rand() desc ";
			$data=$this->__db->query($sql);
			if(isset($data)&&count($data)>0){
				foreach($data as $v){
					$sql="update ll_activity_scratch_prize set scratch_times_day=scratch_times_day-1 where id={$v['id']}";
					if(false==$this->__db->query($sql)){
						FlamingoLogger::getInstance()->Logln('更新奖品当天剩余可抽中次数失败: '.'uin '.$uin.' loginkey '.$loginKey);
						$this->__db->query("rollback");
						return array('code'=>ErrorCode::DataBase_Not_OK, 'result'=>null);
					}
					return $v;
				}
			}
			$prize_rank --;
		}
		return $lottery;
	}
}
