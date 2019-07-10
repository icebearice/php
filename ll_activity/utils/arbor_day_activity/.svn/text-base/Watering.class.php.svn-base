<?php
/*************************************************************************
 * File Name: Watering.class.php
 * Author: lvchao.yan
 * Created Time: 2019.02.22 
 * Desc: 浇水 
 *************************************************************************/
require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";

require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";

class Watering{
	private static $__instance;
	private $__db;
	private $task_day;
	private $today_timestamp;

	private function __construct() {
		$this->__db = new Db();
		$this->__db->use_db("write");
		$this->task_day = date("Y-m-d");
		$this->today_timestamp = strtotime($this->task_day);
	}

	public static function getInstance() { 
		if (!(self::$__instance instanceof self)) {
			self::$__instance = new self;
		}
		return self::$__instance;
	}

	public function showWater($uin){
		$sql = "select water_100, water_500, water_1000 from arbor_day_user_list where uin = {$uin}";
		$res = $this->__db->query($sql);	
		$water = array(
			"100"    => $res[0]["water_100"],
			"500"    => $res[0]["water_500"],
			"1000"   => $res[0]["water_1000"]
		);	

		if ($water["100"] <= 0 && $water["500"] <= 0 && $water["1000"] <= 0) {  //水源已用完
			$sql = "select count(*) count from arbor_day_user_accept_prize_list where 1 = 1";
			$sql.= " and uin = {$uin} and unix_timestamp(accept_time) >= {$this->today_timestamp} and prize_id between 1 and 28";
			$res = $this->__db->query($sql);
			if ($res[0]["count"] >= 3) {  //今日水源已全部使用
				return -2; 
			} else {  //暂未获得水源
				return -3;
			}
		}    

		return $water;
	}

	public function drawPrize($uin, $water){
		$vip_level = UserInfo::getInstance()->getVipLevel($uin);
		if ($vip_level < 3) {
			$vip_level = 0;
		} else {
			$vip_level = 3;
		}
		$sql = "select prize_id, prize_prob from arbor_day_prize_list where prize_vip = {$vip_level} and water = {$water}";	
		$res = $this->__db->query($sql);
		$pro_array = array();
		foreach ($res as $row) {
			$pro_array[$row["prize_id"]] = $row["prize_prob"];
		}
		$prize_id = getRandResult($pro_array);
		$sql = "select prize_total from arbor_day_prize_list where prize_id = {$prize_id}";
		$res = $this->__db->query($sql);
		$count = $res[0]["prize_total"]; /////////////////////////////////
		@file_put_contents('/tmp/ttttttttttttttt.log', date('Y-m-d H:i:s')."---".$prize_id."---".$count."\n\n",  FILE_APPEND);
		if ($res[0]["prize_total"] <= 0) {
			@file_put_contents('/tmp/ttttttttttttttt.log', date('Y-m-d H:i:s')."---奖励发完！\n\n",  FILE_APPEND);
			$sql = "select prize_id from arbor_day_prize_list where prize_vip = {$vip_level} and water = {$water} and prize_total > 10000000";
			$res = $this->__db->query($sql);
			$prize_id = $res[0]["prize_id"];
		}
		return $prize_id;
	}

	public function watering($uin){
		$prize_info = array();
		$water = $this->showWater($uin);
		if ($water["100"] <= 0 && $water["500"] <= 0 && $water["1000"] <= 0) {  //水源已用完
			$sql = "select count(*) count from arbor_day_user_accept_prize_list where 1 = 1";
			$sql.= " and uin = {$uin} and unix_timestamp(accept_time) >= {$this->today_timestamp} and prize_id between 1 and 28";
			$res = $this->__db->query($sql);
			if ($res[0]["count"] >= 3) {  //今日水源已全部使用
				return -2; 
			} else {  //暂未获得水源
				return -3;
			}
		}    

		foreach($water as $key => $value) {
			if ($value > 0) {
				$water = "water_".$key;
				$this->__db->query("start transaction");
				$sql = "update arbor_day_user_list set {$water} = {$water} - 1, water_times = water_times + 1 where uin = {$uin} and {$water} > 0";	
				$this->__db->query($sql);
				$error = $this->__db->db->error;
				$rows = $this->__db->db->affected_rows;
				if ($rows <= 0) {
					$this->__db->query("rollback");
					@file_put_contents('/tmp/arbor_day_activity_error.log', date('Y-m-d H:i:s')."---Watering.class.php.1---".$error."\n\n",  FILE_APPEND);
					return -1;
				}

				$prize_id = $this->drawPrize($uin, $key);
				//$prize_id = 10;  //////////////////////////
				$sql = "update arbor_day_prize_list set prize_total = prize_total - 1 where prize_id = {$prize_id} and prize_total > 0";
				@file_put_contents('/tmp/ttttttttttttttt.log', date('Y-m-d H:i:s')."---".$sql."\n\n",  FILE_APPEND);
				$this->__db->query($sql);
				$error = $this->__db->db->error;
				$rows = $this->__db->db->affected_rows;
				if ($rows <= 0) {
					$this->__db->query("rollback");
					@file_put_contents('/tmp/arbor_day_activity_error.log', date('Y-m-d H:i:s')."---Watering.class.php.2---".$error."\n\n",  FILE_APPEND);
					return -1;
				}

				$sql = "select prize_name, prize_type, vou_id, prize_sour from arbor_day_prize_list where prize_id = {$prize_id} limit 1";
				$res = $this->__db->query($sql);
				$prize_name = $res[0]["prize_name"];
				$prize_type = $res[0]["prize_type"];
				$vou_id     = $res[0]["vou_id"];
				$prize_sour = $res[0]["prize_sour"];
				$end_time   = "0000-00-00";
				$prize_info = array("water" => $key, "prize_id" => $prize_id, "prize_type" => $prize_type, "prize_name" => $prize_name);

				if ($prize_type == 1) {
					$obj = new LLVoucherServer();
					$info = $obj->getVoucherInfo($vou_id);
					$end_time = date("Y-m-d",$info['expire_time']);					
				}

				$sql = "insert into arbor_day_user_accept_prize_list (uin, prize_id, prize_name, prize_sour, end_time)";
				$sql.= " values({$uin}, {$prize_id}, '{$prize_name}', '{$prize_sour}', '{$end_time}')";
				$this->__db->query($sql);
				$error = $this->__db->db->error;
				$rows = $this->__db->db->affected_rows;
				if ($rows <= 0) {
					$this->__db->query("rollback");
					@file_put_contents('/tmp/arbor_day_activity_error.log', date('Y-m-d H:i:s')."---Watering.class.php.3---".$error."\n\n",  FILE_APPEND);
					return -1;
				}

				if (!$this->__db->query("commit")) {
					$error = $this->__db->db->error;
					$this->__db->query("rollback");
					@file_put_contents('/tmp/arbor_day_activity_error.log', date('Y-m-d H:i:s')."---Watering.class.php.4---".$error."\n\n",  FILE_APPEND);
					return -1;
				}
				
				return $prize_info;
			}
		}	
	}
}
