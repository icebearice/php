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

	public function getUserAllAcceptPrize($uin){  //获取用户获取的所有奖励信息
		$sql = "select arbor_day_user_accept_prize_list.prize_id, arbor_day_user_accept_prize_list.prize_name";
		$sql.= ", accept_time, prize_type, prize_num, arbor_day_user_accept_prize_list.jd_card, prize_use_cond, end_time";
		$sql.= " from arbor_day_user_accept_prize_list, arbor_day_prize_list where arbor_day_user_accept_prize_list.prize_id = arbor_day_prize_list.prize_id";
		$sql.= " and uin = {$uin} and arbor_day_user_accept_prize_list.prize_id not in(30, 31, 32)";
		$res = $this->__db->query($sql);

		return array_reverse($res);  //按前端要求：倒序
	}
}
