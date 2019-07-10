<?php

/**
 * 摘金球领取奖励
 */

require_once dirname(dirname(__FILE__))."/include/config.inc.php";
require_once dirname(dirname(__FILE__))."/include/config.php";

require_once SYSDIR_UTILS."/logger.php";
require_once SYSDIR_UTILS."/DB.php";
require_once SYSDIR_UTILS."/error.class.php";
require_once SYSDIR_UTILS."/LLActivityAggPrize.php";
require_once SYSDIR_UTILS."/LLActivityTaskManager.php";


class LLActivityGoldenBollPrizeManager {
	private $__db;
	public function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("read");
	}


	public function getGoldBallPrize($uin,$tid,$login_key, $uuid, $productID, $platform, $appid) {
		if (!in_array($tid,Golden_Ball_Task)) {
			return null;
		}
		$taskManager = new LLActivityTaskManager();
		$taskInfo = $taskManager->getUserTaskInfo($uin,$tid);
		//print_r($taskInfo);die;
		if (isset($taskInfo)) {
			if($taskInfo['status'] != 2) {//已完成的任务才能领取
			   return null;
			}
		}
        $prize_info = $this->getPrizeByTask($taskInfo['task_id']);
		if(!$prize_info) return null; 
		//print_r($prize_info);die;

		if ($prize_info) {
			$give_obj = new LLActivityAggPrizeManager();
			if (false === $give_obj->giveUserPrize($uin, $prize_info[0]['id'], $login_key, $uuid, $productID, $platform, $appid)) {
				return null;
			}
		}

		$updateData['completed_num'] = 1;
		$updateData['status'] = 3;
		$this->__db->use_db("write");
		$this->__db->update("ll_activity_user_task_list", $updateData,['id'=>$taskInfo['id']]);
		return array(
			'id' =>$prize_info[0]['id'],
			'prize_name'=>$prize_info[0]['prize_name'],
			'icon'=>$prize_info[0]['icon'],
			'prize_type'=>$prize_info[0]['prize_type'],
		);
	} 


	public function getPrizeByTask($task_id){
		$prize_name = '';
		switch($task_id){
		    case 1:
				$prize_name = '20点成长值';
				break;
			case 2:
				$prize_name = '3元代金券';
				break;
			case 3:
				$prize_name = '1元代金券';
				break;
		}

		$table = 'll_activity_double_egg_prize';
        $prize_info = $this->__db->select($table,'id,prize_name,prize_type,icon',"prize_name = '$prize_name' and completed_num<limited_num and atype = 2");  
		//echo $this->__db->get_sql();die;
		return $prize_info;
	}

	public function getPrizeMoney($prize_name){
		if($prize_name == '20点成长值') {
			return 20;
		}
		if($prize_name == '3元代金券') {
			return 3;
		}
		if($prize_name == '1元代金券') {
			return 1;
		}
	}

}
