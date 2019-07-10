<?php
/*************************************************************************
 * File Name: AcceptYearPrize.class.php
 * Author: lvchao.yan
 * Created Time: 2018.12.20
 * Desc: 打年货 
 *************************************************************************/
require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";

require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";


class AcceptYearPrize{
	private static $__instance;
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

	public function showYearPrize($prize_id = 0) {  //获取年货信息
		$sql = "select prize_id,prize_name,vou_id,prize_type,prize_total,prize_vip_level,prize_icon from ll_activity_prize_list where 1 = 1";
		if ($prize_id > 0) {
			$sql .= " and prize_id = {$prize_id}";
		} else {
			$sql .= " and prize_id between 1 and 6";
		}
		$res = $this->__db->query($sql);
		return $res;
	}

	public function getUserAcceptPrizeId($uin){  //获取用户今日已领取年货id
		$today = strtotime(date("Y-m-d"));  //今日零时时间戳
		$accept_prize_id = array();
		$sql = "select prize_id from ll_activity_user_accept_prize_info where uin = {$uin} and prize_id between 1 and 6 and unix_timestamp(accept_time) >= {$today}";
		$result = $this->__db->query($sql);
		if (count($result) != 0){
			foreach($result as $res){
				$accept_prize_id[] = $res['prize_id'];
			}
		}
		return $accept_prize_id;
	}

	public function getYearPrize($uin,$prizeId){  //用户领取年货
		$uuid = isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : "";  //APP
		if (!$uuid) {
			if (isset($_SERVER['HTTP_USER_AGENT'])) {
				$uuid = md5("ll_web_login_{$_SERVER['HTTP_USER_AGENT']}");
			}
		}
		$product_id = isset($_REQUEST['productID']) ? $_REQUEST['productID'] : 151;

		$sql = "select prize_name,prize_icon,prize_sour,prize_type,vou_id from ll_activity_prize_list where prize_id = {$prizeId}";
		$res = $this->__db->query($sql);
		if (!$res) {
			return -1;
		}

		$prize_sour = $res[0]['prize_sour'];
		$prize_name = $res[0]['prize_name'];
		$end_time = '0000-00-00';
		if ($res[0]['prize_type'] == 2) {  //写入代金券有效期
			$obj = new LLVoucherServer();
			$info = $obj->getVoucherInfo($res[0]['vou_id']);
			$end_time = date("Y-m-d",$info['expire_time']);
		}
		$this->__db->query('start transaction');
		$sql = "update ll_activity_prize_list set prize_total = prize_total-1 where prize_id = {$prizeId}";
		$this->__db->query($sql);
		$update_rows = $this->__db->db->affected_rows;
		$sql = "insert into ll_activity_user_accept_prize_info (uin,prize_id,prize_name,prize_sour,end_time) values({$uin},'{$prizeId}','{$prize_name}','{$prize_sour}','{$end_time}')";
		$this->__db->query($sql);
		$insert_rows = $this->__db->db->affected_rows;
		$flag = giveUserPrize($uin,$prizeId,$uuid,$product_id);  //发放奖励  
		if ($update_rows<=0 || $insert_rows<=0 || !$flag || !$this->__db->query('commit')) {
			$this->__db->query('rollback');
			return -1;
		}

		return 1;
	}
}
