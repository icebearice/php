<?php
/*************************************************************************
 * File Name: MarkSexRoller.class.php
 * Author: lvchao.yan
 * Created Time: 2018.12.22
 * Desc: 六合彩滚筒
 *************************************************************************/

require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";

require_once SYSDIR_UTILS . "/logger.php";
require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/userAuthServer.class.php";
require_once SYSDIR_UTILS . "/userInfoServer.class.php";
require_once SYSDIR_UTILS . "/XXRequestBase.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

class MarkSexRoller{
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

	public function getUserGoTimes($uin){
		$sql = "select go_times from ll_activity_user_list_of_activity where uin = {$uin}";
		$res = $this->__db->query($sql);
		$go_times = count($res)? $res[0]['go_times'] : 0;
		return $go_times;
	}

	public function getUserCollectNumCount($uin){
		$num_count = array();
		$sql = "select num,num_count from ll_activity_user_collected_number_count where uin = {$uin} order by num asc";
		$res = $this->__db->query($sql);
		if (!count($res)) return $num_count;
		foreach($res as $v){
			$num_count[intval($v['num'])] = intval($v['num_count']);
		}
		return $num_count;
	}

	public function click_go($uin){  //GO
		$sql = "select go_times from ll_activity_user_list_of_activity where uin = {$uin}";
		$res = $this->__db->query($sql);
		$go_times = $res[0]['go_times'];
		if ($go_times == 0) {
			return -2;  //GO次数不足
		}

		$this->__db->query('start transaction');

		$sql = "update ll_activity_user_list_of_activity set go_times = go_times - 1 where uin = {$uin} and go_times > 0";
		$this->__db->query($sql);  //GO次数减1

		$row = $this->__db->db->affected_rows;
		if ($row <= 0) {
			$this->__db->query('rollback');
			return -1;
		}

		$pro_array = [  //0-9各自的概率
			0 =>  5,
			1 => 10,
			2 => 10,
			3 => 10,
			4 => 10,
			5 => 10,
			6 => 15,
			7 => 10,
			8 => 10,
			9 => 10
		];
		$num = getRandResult($pro_array);  //抽数字
		$sql = "select * from ll_activity_user_collected_number_count where uin = {$uin} and num = {$num}";
		if (count($this->__db->query($sql)) > 0) {  //若该用户已经抽到过相同数字
			$sql = "update ll_activity_user_collected_number_count set num_count = num_count+1 where uin = {$uin} and num = {$num}";
			$this->__db->query($sql);

			$row = $this->__db->db->affected_rows;
			if ($row <= 0) {
				$this->__db->query('rollback');
				return -1;
			}

		}else{  //反之
			$sql = "insert into ll_activity_user_collected_number_count (uin,num) values({$uin},{$num})";
			$this->__db->query($sql);

			$row = $this->__db->db->affected_rows;
			if ($row <= 0) {
				$this->__db->query('rollback');
				return -1;
			}

		}

		if (!$this->__db->query('commit')) { 
			$this->__db->query('rollback');
			return -1;
		}

		return [$num,$go_times - 1];
	}

	public function swop_prize($uin){  //合成奖励
		$prize_info = array();
		$num_count = $this->getUserCollectNumCount($uin);
		$num = array();  //收集的数字集合                                                               
		foreach($num_count as $key => $value){
			if($value) $num[] = $key;  //值不为0,取键
		}
		if (!(in_array(2,$num)&&in_array(0,$num)&&in_array(1,$num)&&in_array(9,$num))) {  //判断是否集齐2019
			return -2;  //没有集齐2019
		}	
		$this->__db->query('start transaction');

		$sql = "update ll_activity_user_collected_number_count set num_count = num_count-1 where uin = {$uin} and num in(2,0,1,9) and num_count > 0";
		$this->__db->query($sql);

		$row = $this->__db->db->affected_rows;
		if ($row != 4) {
			$this->__db->query('rollback');
			return -1;
		}

		$pro_array = [24 => 10, 25 => 10, 26 => 20, 27 => 30, 28 => 10, 29 => 20];
		$prize_id = 0;
		while (1) {  //合成奖励
			$rand_prize_id = getRandResult($pro_array);
			$sql = "select prize_total from ll_activity_prize_list where prize_id = {$rand_prize_id}";
			$res = $this->__db->query($sql);
			$prize_total = $res[0]['prize_total'];
			if ($prize_total <= 0) {  //奖品数量为0，从奖池里剔除
				unset($pro_array[$rand_prize_id]);
			} else {
				$prize_id = $rand_prize_id;
				$sql = "update ll_activity_prize_list set prize_total = prize_total - 1 where prize_id = {$rand_prize_id} and prize_total > 0";		
				$this->__db->query($sql);
				$row = $this->__db->db->affected_rows;
				if ($row <= 0) {
					$this->__db->query('rollback');
					return -1;
				}
				break;
			}
		}

		$sql = "select prize_name,prize_icon,prize_sour,prize_type,vou_id from ll_activity_prize_list where prize_id = {$prize_id}";
		$res = $this->__db->query($sql);
		$prize_sour = $res[0]['prize_sour'];
		$prize_name = $res[0]['prize_name'];
		$end_time = '0000-00-00';
		if ($res[0]['prize_type'] == 2) {
		    $obj = new LLVoucherServer();               
			$info = $obj->getVoucherInfo($res[0]['vou_id']);
			$end_time = date("Y-m-d",$info['expire_time']);
		}
		$sql = "insert into ll_activity_user_accept_prize_info (uin,prize_id,prize_name,prize_sour,end_time)";
		$sql.= " values({$uin},'{$prize_id}','{$prize_name}','{$prize_sour}','{$end_time}')";
		$this->__db->query($sql);

		$row = $this->__db->db->affected_rows;
		if ($row <= 0) {
			$this->__db->query('rollback');
			return -1;
		}

		$prize_info = array('prize_id'=>$prize_id,'prize_name'=>$prize_name,'prize_icon'=>$res[0]['prize_icon']);

		if (!$this->__db->query('commit')) { 
			$this->__db->query('rollback');
			return -1;
		}

		return $prize_info;
	}
}
