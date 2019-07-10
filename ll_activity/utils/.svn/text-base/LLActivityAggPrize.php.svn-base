<?php
require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/grouthServer.class.php";
require_once SYSDIR_UTILS."/voucherServer.class.php";
require_once SYSDIR_UTILS."/userInfoServer.class.php";
require_once SYSDIR_UTILS."/XXRequestBase.php";
require_once SYSDIR_UTILS."/error.class.php";


class LLActivityAggPrizeManager {
	private $__db;
	private $login_key = 'test-flamingo-login-key-abc';
	public function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("write");
	}

	public function userGetEggs($uin, $login_key, $uuid, $productID=151,$platform=102,$appid = 0) {
		$taskManager = new LLActivityTaskManager();
		$taskInfo = $taskManager->getUserTaskInfo($uin,Broken_Egg_Task);
		if (isset($taskInfo)) {
			return 404;
		}
		 
                $ipLimit = $this->ipLimit();
                if ($ipLimit > 10) {
                   return 400;          
                }
		
		$level = $this->getUserLevel($uin,$login_key,$uuid,$productID,$platform,$appid);
		$prizeInfo = $this->getLotteryInfo($level);
		if (!$prizeInfo) {
			return null;
		}
		file_put_contents('/tmp/activity.log','砸金蛋：'.var_export($prizeInfo,true)."\r\n",FILE_APPEND);
		/*$prizeInfo = [
			'id' => 4
		];
		 */
		 
		if ($prizeInfo) {
			if (false === $this->giveUserPrize($uin, $prizeInfo['id'], $this->login_key,'',151,102,0,Broken_Egg_Task)) {
				//print_r($prizeInfo);die;
				return null;
			}
                        
                      
		}
		$insertData = array();
		$insertData['uin'] = $uin;
		$insertData['task_id'] = Broken_Egg_Task;
		$insertData['task_time'] = date("Y-m-d");
		$insertData['completed_num'] = 1;
		$insertData['first_add_time'] = date("Y-m-d H:i:s");
		$insertData['update_time'] = date("Y-m-d H:i:s");
		$insertData['ip'] = getIp();
		$this->__db->use_db("write");
		$this->__db->insert("ll_activity_user_task_list", $insertData);
		return array(
			'id' =>$prizeInfo['id'],
			'prize_name'=>$prizeInfo['prize_name'],
			'icon'=>$prizeInfo['icon'],
			'prize_type'=>$prizeInfo['prize_type'],
		);
	} 


        public function ipLimit(){
            $ip = getIp();
            $t = date('Y-m-d');
            $sql = "select count(*) as num from ll_activity_user_task_list where ip = '{$ip}' and task_time = '{$t}'";
            @file_put_contents('/tmp/activity.log','砸金蛋ip',$sql."\r\n",FILE_APPEND);
            $total_info = $this->__db->query($sql);
            $num = 0;
            if ($total_info) {
                $num = $total_info[0]['num'];
            }
            return $num;
           
        }

	public function getUserLevel($uin,$loginKey,$uuid,$productID,$platform,$appid = 0) {
		$obj = new LLGrouthServer();
		$res = $obj->getUserVipLevel($uin,$loginKey,$uuid,$productID,$platform,$appid);
		if ($res) {
		   return $res['vip_level'];
		}
		return 0;
	}

	public function getLotteryInfo_bak($level){
		$sum = 0;
		if ($level <= 3) {
			$sql = "select * from ll_activity_double_egg_prize  where completed_num<limited_num and atype = 1 and vip_level = 0";
		}else {
			$sql = "select * from ll_activity_double_egg_prize  where completed_num<limited_num and atype = 1 and vip_level >= 4";
		}
		file_put_contents('/tmp/activity.log','砸金蛋'.$sql."\r\n",FILE_APPEND);
		$res=$this->__db->query($sql);
		file_put_contents('/tmp/activity.log','砸金蛋'.var_export($res,true)."\r\n",FILE_APPEND);
		if($res === FALSE){
			return 	null;
		}
		shuffle($res);
        
		foreach($res as $v){
			$sum += $v['probability'];
		}
		file_put_contents('/tmp/activity.log','砸金蛋total'.$sum."\r\n",FILE_APPEND);
		$lottery = rand(1,$sum);
		file_put_contents('/tmp/activity.log','砸金蛋'.$lottery."\r\n",FILE_APPEND);
		
		foreach($res as $v) {
			if($lottery<=$sum){
				 return $v;
			}
		}
	}

	public function getLotteryInfo($level){
		$sum = 0;
		if ($level <= 3) {
			$sql = "select * from ll_activity_double_egg_prize  where completed_num<limited_num and atype = 1 and vip_level = 0";
		}else {
			$sql = "select * from ll_activity_double_egg_prize  where completed_num<limited_num and atype = 1 and vip_level >= 4";
		}
		file_put_contents('/tmp/activity.log','砸金蛋'.$sql."\r\n",FILE_APPEND);
		$res=$this->__db->query($sql);
		file_put_contents('/tmp/activity.log','砸金蛋'.var_export($res,true)."\r\n",FILE_APPEND);
		if($res === FALSE){
			return 	null;
		}
                $prize_info = $this->getRandPrize($res);
                return $prize_info;
	}

    /**
	* 按照奖品概率随机选
	* @param $prizes
	* @return array
	*/

	public function getRandPrize($prizes) {
		$prize = array();
		foreach ($prizes as $key => $val) {
			$arr[$key] = $val['probability'];
		}
		$proSum = array_sum($arr);
		asort($arr);
		foreach ($arr as $k => $v) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $v) {
				$prize = $prizes[$k];
				break;
			} else {
				$proSum -= $v;
			}
		}
		return $prize;
	}
 

	public function giveUserPrize($uin, $pid, $login_key, $uuid, $productID=151,$platform=102,$appid = 0,$tid=0) {
		$sql = "select * from ll_activity_double_egg_prize where id = {$pid}";
		$info = $this->__db->query($sql);
		if (!isset($info) || count($info) <= 0) {
			return false;
		}

		$info = $info[0];
		$isDo = false;
		$insertData=array(
			'uin'=>$uin,
			'prize_id'=>$pid,
			'status'=>1,
			'add_time'=>date('Y-m-d H:i:s'),
			'money_status'=>0,
			'task_id' =>$tid
		);

		if ($info['prize_type'] == "代金券") {
			$voucher = new LLVoucherServer();
			if (TRUE === $voucher->sendVoucher($uin, $login_key, $uuid, $productID,$platform, $info['money_id'], $appid)) {
				$insertData['money_status'] = 1;
				FlamingoLogger::getInstance()->Logln('代金券发放成功: '.'uin '.$uin.' loginkey '.$login_key.' money_id '.$info['money_id']);
			}else {
				$insertData['money_status'] = 0;
				FlamingoLogger::getInstance()->Logln('代金券发放失败: '.'uin '.$uin.' loginkey '.$login_key.' money_id '.$info['money_id']);
				return false;
			}
		}
		if ($info['prize_type'] == "成长值") {
			$grouth = new LLGrouthServer();
			if (true === $grouth->addGrouthValue($uin,$info['money'],$login_key, $uuid, $productID, $platform,$appid)) {
				$insertData['money_status'] = 1;
				FlamingoLogger::getInstance()->Logln('成长值发放成功: '.'uin '.$uin.' loginkey '.$login_key.' money '.$info['money']);
			}
			else {
				$insertData['money_status'] = 0;
				FlamingoLogger::getInstance()->Logln('成长值发放失败: '.'uin '.$uin.' loginkey '.$login_key.' money '.$info['money']);
				return false;
			}

		}
		$sql = "update ll_activity_double_egg_prize set completed_num = completed_num + 1 where id = {$pid}";
		$this->__db->query($sql);
		$res = $this->__db->insert('ll_activity_prize_log',$insertData);
		return true;
	}

	/**                                                                                                           
	 * 发放代金劵                                                                                                 
	 * @param $uin 用户uin                                                                                        
	 * @param $vid 代金劵ID                                                                                       
	 * @param $desc 发放说明                                                                                      
	 * @param $money 自定义金额                                                                                   
	 * @return bool                                                                                               
	 */                                                                                                           

	public function grantVoucher($uin, $vid, $desc, $money = '') {                                                
		if (empty($uin) || empty($vid)) {                                                                         
			return false;                                                                                         
		}                                                                                                         
		$reqArr = array(                                                                                          
			'vid' => $vid,                                                                                        
			't' =>  time(),                                                                                       
			'key' => PAYCENTER_KEY,                                                                               
			'desc' => $desc,                                                                                      
			'nums' => 1,                                                                                          
			'uin' => $uin                                                                                         
		);                                                                                                        
		if (!empty($money)) {                                                                                     
			$reqArr['money'] = $money;                                                                            
		}                                                                                                         
		$reqArr['sign'] = create_verify($reqArr, PAYCENTER_SECRET);                                               
		$response = make_request(LL_GRANT_VOUCHER_API, $reqArr, 300, "GET");                                      
		file_put_contents('/tmp/double_twelve/grant_voucher.log',date('Y-m-d H:i:s') . ' ' . __FILE__ . ':' . __LINE__
			. "\n" . var_export($response,true) . "\n", FILE_APPEND );                                                   
		if ($response['code'] != 200) {                                                                           
			return false;                                                                                         
		}                                                                                                         
		$result = json_decode($response['result'], true);                                                         
		if($result['code'] != 200 && $result['msg'] != 'success'){                                                
			return false;                                                                                         
		}                                                                                                         
		return true;                                                                                              
	}                                                                                                             

}
