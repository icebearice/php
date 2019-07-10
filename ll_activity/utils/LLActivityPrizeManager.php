<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/logger.php";

require_once SYSDIR_UTILS."/voucherServer.class.php";

class LLActivityPrizeManager{
	private $__db;

	public function __construct(){
		$this->__db=new Db();
		$this->__db->use_db("write");
	}

	public function getPrizeRecords($uin,$start,$count){
		$result = array();
		$info=array();
		$sql = "select a.prize_name,b.id,b.add_time from ll_activity_prize a inner join ll_activity_prize_log b on a.id=b.prize_id where b.uin={$uin} order by b.add_time desc limit {$start},{$count};";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
		}
		foreach($res as $v){
			$info['id']=$v['id'];
			$info['prize_info']=$v['prize_name'];
			$info['time']=$v['add_time'];
			$info['prize_type']=School_Bag_Command;
			$info['sort_num']='';
			$result[]=$info;
		}
		return array('code'=>ErrorCode::OK,'result'=>$result);
	}

	public function getPrizeInfo($limit_num){
		$result = array();
		$info=array();
		$sql = "select b.id,uin,prize_name from ll_activity_prize a inner join ll_activity_prize_log b on a.id=b.prize_id limit {$limit_num}";
		$res = $this->__db->query($sql);
		if($res === FALSE) {
			return array('code'=> ErrorCode::DataBase_Not_OK,'result'=>null);
		}
		$userHandler = new LLUserInfoServer();
		foreach($res as $v){
			$info['id']= $v['id'];
			$info['reward_info']=$v['prize_name'];
			$info['user_name']=$userHandler->getUserNameByUin($v['uin']);
			$result[]=$info;
		}
		return array('code'=>ErrorCode::OK,'result'=>$result);
	}

	private function getCoverName($name){
		return mb_substr($name,0,mb_strlen($name,'UTF-8')-2, 'UTF-8').'**';
	}

	public function addPrizeDivide($uin,$limit_credit,$loginKey,$uuid,$productID,$platform){
		$this->__db->query("start transaction");
		$code = $this->getCondition($uin,$limit_credit);
		if($code !== ErrorCode::OK){
			return array('code'=>$code,'result'=>null);
		}
		$res = $this->getLotteryInfo();
		if($res['code']!==0){
			 return array('code'=>$res['code'],'result'=>null);
		}
		$prize_id = $res['result'];
		$money_id = $res['money_id'];
		if($this->updatePrizeNum($prize_id) === FALSE){
			$this->__db->query("rollback");
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);;
		}
		if($this->insertPrizeLog($uin,$prize_id,$money_id,$loginKey,$uuid,$productID,$platform) === FALSE){
			$this->__db->query("rollback");
			return array('code'=>ErrorCode::DataBase_Not_OK,'result'=>null);
		}
		$result['prize']=$this->getPrizeMoney($prize_id);
		if(!isset($result['prize'])){
			return array('code'=>ErrorCode::DataBase_Not_OK, 'result'=>null);
		}
		$result['user_name']= (new LLUserInfoServer())->getUserNameByUin($uin);
		$this->__db->query("commit");
		return array('code'=>ErrorCode::OK,'result'=>$result);
	}

	private function getPrizeMoney($prize_id){
		$sql = "select money from ll_activity_prize where id = {$prize_id};";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return null;
		}
		foreach($res as $v){
			return $v['money'];
		}
		return null;
	}

	private function getCondition($uin,$limit_credit){
		$userHandler = new LLUserInfoServer();
		$userInfo = $userHandler->getUserInfoByUin($uin);
		if(!isset($userInfo)){
			return ErrorCode::User_Phone_Not_Get; 
		}
		$userPhone = $userInfo->getBase_data()->getUphone();
		if(empty($userPhone)){
			return ErrorCode::User_Not_Phone;
		}
		
		$sql = "select uin from ll_activity_prize_log where uin = {$uin} and status=1;";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return ErrorCode::DataBase_Not_OK;
		}
		if(!empty($res)){
			return ErrorCode::User_Prize_Divide_Had_Exist;
		}

		$sql = "select credit from ll_activity_credit where uin = {$uin} and credit>={$limit_credit};";
		$res = $this->__db->query($sql);
		if($res === FALSE){
			return ErrorCode::DataBase_Not_OK;
		}
		if(empty($res)){
			return ErrorCode::User_Prize_Divide_Not_Enough;
		}
		return ErrorCode::OK;
	}	

	public function getLotteryInfo(){
		$probability_map = array();
		$lottery = rand(1,100);
		$sum = 0;
		$sql = "select id,probability,money_id from ll_activity_prize where completed_num<limited_num;";
		$res=$this->__db->query($sql);
		if($res === FALSE){
			return array('code'=>ErrorCode::DataBase_Not_OK,'money_id'=>null,'result'=> null);
		}
		foreach($res as $v){
			$sum += $v['probability'];
			if($lottery<=$sum){
				return array('code'=>ErrorCode::OK,'money_id'=>$v['money_id'],'result'=> $v['id']);
			}
		}
		//抽不中默认给一元代金券
		return array('code'=>ErrorCode::OK,'money_id'=>Money_Id_One,'result'=>Prize_Id_One);
	}

	private function updatePrizeNum($id){
		$update_time = date('Y-m-d H:i:s');
		$sql = "update ll_activity_prize set completed_num = completed_num+1 , update_time='{$update_time}'  where id = {$id};";
		$res = $this->__db->query($sql);
		return $res;
	}

	private function insertPrizeLog($uin,$prize_id,$money_id,$loginKey,$uuid,$productID,$platform){
		$insertData=array(
			'uin'=>$uin,
			'prize_id'=>$prize_id,
			'status'=>1,
			'add_time'=>date('Y-m-d H:i:s'),
			'money_status'=>0
		);
		$voucherServer = new LLVoucherServer();
		if(TRUE===$voucherServer->sendVoucher($uin,$loginKey,$uuid,$productID,$platform,$money_id)){
			$insertData['money_status']=1;
			FlamingoLogger::getInstance()->Logln('代金券发放成功: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
		}else {
			$insertData['money_status']=2; //发送失败，未领取
			FlamingoLogger::getInstance()->Logln('代金券发放失败: '.'uin '.$uin.' loginkey '.$loginKey.' money_id '.$money_id);
		}
					
		$res = $this->__db->insert('ll_activity_prize_log',$insertData);
		return $res;
	}

}	
