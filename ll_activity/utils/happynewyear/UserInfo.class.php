<?php
/*************************************************************************
 * File Name: UserInfo.class.php
 * Author: lvchao.yan
 * Created Time: 2018.12.25     
 * Desc: 用户信息     
 *************************************************************************/

require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";

require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/LLActivityTaskManager.php";
require_once SYSDIR_UTILS . "/LLActivityBaseData.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/voucherServer.class.php";

class UserInfo{
	public static $__instance;
	private $__db;

	private function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("write");
	}

	public static function getInstance() {
		if (!(self::$__instance instanceof self)) {
			self::$__instance = new self;
		}   
		return self::$__instance;
	}

	public function getUserData($uin){  //获取用户数据
		$user_data_arr = null;
		$obj = new LLUserInfoServer();
		$user_data = $obj->getUserInfoByUin($uin);
		if($user_data) {
			$user_data_arr = json_decode(json_encode($user_data),true);
		}
		return $user_data_arr;
	}

	public function getVipLevel($uin){  //返回用户VIP等级，用数字表示
		$this->__db->use_db("llbackend");
		$sql = "SELECT vip_level FROM ll_user_vip_list WHERE uin = '{$uin}' LIMIT 1";
		$res = $this->__db->query($sql);
		if(empty($res)){
			return 0;
		}   
		$vip_level = $res[0]['vip_level'] > 0 ? $res[0]['vip_level'] : 0;
		return $vip_level;
	}

	public function isUserPhone($uin){  //用户绑定手机验证，绑定返回1，反之返回0
		$isPhone = 0;
		$user_data_arr = $this->getUserData($uin);
		if($user_data_arr) {
			if(isset($user_data_arr['base_data']['uphone'])) {
				$isPhone = 1;
			}
		}
		return $isPhone;
	}

	public function getUserTodayCost($uin){  //获取今日花费
		$this->__db->use_db("write");
		$today = date("Y-m-d");  //今日零时时间戳
		$sql = "select num from ll_activity_user_cost_of_year where uin = {$uin} and task_time = '{$today}'";
		$res = $this->__db->query($sql);
		$cost = $res[0]['cost'] ? $res[0]['cost'] : 0;
		return $cost;
	}

	public function getUserAllCost($uin){  //获取从活动开始到此刻为止的所有花费
		$sql = "select sum(num) all_cost from ll_activity_user_cost_of_year where uin = {$uin}";
		$res = $this->__db->query($sql);
		$all_cost = $res[0]['all_cost'] ? $res[0]['all_cost'] : 0;
		return $all_cost;
	}

	public function isUserTodayTopic($uin){  //查询用户今日是否参与话题
		$this->__db->use_db("lldaliytopic");
		$today = strtotime(date("Y-m-d"));  //今日零时时间戳
		$sql = "select*from ll_reply_list where uid = {$uin} and add_time >= {$today}";
		$res = $this->__db->query($sql);
		if (count($res) > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function getUserShareTimes($uin){  //查询用户今日邀请好友接力次数
		$this->__db->use_db("write");
		$today = strtotime(date("Y-m-d"));  //今日零时时间戳
		$sql = "select * from user_share_count where uin = {$uin} and time_stamp >= {$today}";
		$res = $this->__db->query($sql);
		$share_times = count($res);
		return $share_times;
	} 

	public function getUserAllAcceptPrize($uin){  //获取用户获取的所有奖励信息
		$sql = "select ll_activity_user_accept_prize_info.prize_id,ll_activity_user_accept_prize_info.prize_name";
		$sql.= ",ll_activity_user_accept_prize_info.prize_sour,accept_time,prize_type,prize_num,vou_id,prize_use_cond,end_time";
		$sql.= " from ll_activity_user_accept_prize_info,ll_activity_prize_list where 1 = 1";
		$sql.= " and ll_activity_user_accept_prize_info.prize_id = ll_activity_prize_list.prize_id and uin = {$uin}";
		$res = $this->__db->query($sql);

		return array_reverse($res);  //按前端要求：倒序
	}
}
