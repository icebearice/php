<?php
/*************************************************************************
 * File Name:ContriValuePK.class.php
 * Author: lvchao.yan
 * Created Time: 2018.12.26
 * Desc: 贡献值PK
 *************************************************************************/

require_once dirname(dirname(__DIR__)) . "/include/config.php";
require_once dirname(dirname(__DIR__)) . "/include/config.inc.php";

require_once SYSDIR_UTILS . "/DB.php";
require_once SYSDIR_UTILS . "/error.class.php";
require_once SYSDIR_UTILS . "/happynewyear/ContriValuePK.class.php";
require_once SYSDIR_UTILS . "/happynewyear/UserInfo.class.php";
require_once SYSDIR_UTILS . "/happynewyear/commonFunction.php";

class ContriValuePK{
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

	public function getThreeUserData($uin){
		$three_user_data = array();  //返回数据：三个随机用户的ID、昵称、头像
		$sql = "select uin from ll_activity_user_list_of_activity where uin != {$uin}";
		$res = $this->__db->query($sql);
		$user_id_arr = array();  //所有活动用户的ID
		foreach($res as $u){
			$user_id_arr[] = $u['uin'];
		}
		$three_user_index = array_rand($user_id_arr,3);  //随机获取三个用户的ID索引
		$three_user_id = array();  //三个随机用户的ID
		foreach($three_user_index as $index){
			$three_user_id[] = $user_id_arr[$index];
		}
		$obj = UserInfo::getInstance();
		foreach($three_user_id as $id){
			$user_data = $obj->getUserdata($id);
			$user_nickname = isset($user_data['base_data']['unickname']) ? $user_data['base_data']['unickname'] : "可爱的66玩家";
			$user_icon = isset($user_data['ext_data']['uico']) ? $user_data['ext_data']['uico'] : '';
			$three_user_data[] = array('uin'=>$id,'nickname'=>$user_nickname,'icon'=>$user_icon);
		}
		return $three_user_data;
	}

	public function getUserPKWinCount($uin){
		$pk_win_count = 0;
		$sql = "select pk_win_count from ll_activity_user_list_of_activity where uin = {$uin}";
		$res = $this->__db->query($sql);
		if (count($res)){
			$pk_win_count = $res[0]['pk_win_count'];
		}
		return $pk_win_count;
	}

	public function getPrizeOfWin(){
		$pro_array = [
			 7 => 34,
			 8 => 23,
			 9 => 18,
			10 => 11,
			11 =>  7,
			12 =>  5,
			13 =>  2
		];
		$get_prize_id = getRandResult($pro_array);
		return $get_prize_id;
	}

	public function pkHim($uin,$his_id){
		$sql = "select pk_times,pk_win_count from ll_activity_user_list_of_activity where uin = {$uin}";
		$res = $this->__db->query($sql);
		$pk_times = $res[0]['pk_times'];
		$my_pk_win_count = $res[0]['pk_win_count'];
		if ($pk_times <= 0){  //剩余PK次数不足
			return -2;
		}

		$result = array();
		$data = UserInfo::getInstance()->getUserData($his_id);
		if(!$data){
			return -3;  //$his_id无效
		}

		$this->__db->query('start transaction');

		/* PK次数减1 */
	$sql = "update ll_activity_user_list_of_activity set pk_times = pk_times - 1, all_pk_times = all_pk_times - 1 where uin = {$uin} and pk_times > 0";
	    $sql.= " and pk_times > 0";
		$this->__db->query($sql);

		$row = $this->__db->db->affected_rows;
		if ($row <= 0){
			$this->__db->query('rollback');
			return -1;
		}

		/* 每日任务入库 */
		$today = date("Y-m-d");  //今日年月日
		$sql = "select uin from ll_activity_user_complete_daily_task_info where uin = {$uin} and task_id = 4 and task_day = '{$today}'";
		$res = $this->__db->query($sql);
		if (count($res) == 0) {  //每日只入库1次,同时GO加1
			$task_day = date("Y-m-d");
			$sql = "insert into ll_activity_user_complete_daily_task_info (uin,task_id,task_name,task_times,task_day) values({$uin},4,'参与每日贡献值PK',1,'{$task_day}')";
			$this->__db->query($sql);

			$row = $this->__db->db->affected_rows;
			if ($row <= 0){
				$this->__db->query('rollback');
				return -1;
			}

			$sql = "update ll_activity_user_list_of_activity set go_times = go_times + 1 where uin = {$uin}";
			$this->__db->query($sql);                                                                                                                    

			$row = $this->__db->db->affected_rows;
			if ($row <= 0){
				$this->__db->query('rollback');
				return -1;
			}

		}

		$my_data = UserInfo::getInstance()->getUserdata($uin);
		$my_nickname = isset($my_data['base_data']['unickname']) ? $my_data['base_data']['unickname'] : "可爱的66玩家";
		$my_icon = isset($my_data['ext_data']['uico']) ? $my_data['ext_data']['uico'] : '';
		$my_contri_value = UserInfo::getInstance()->getUserAllCost($uin);
		$his_data = UserInfo::getInstance()->getUserdata($his_id);
		$his_nickname = isset($his_data['base_data']['unickname']) ? $his_data['base_data']['unickname'] : "可爱的66玩家";
		$his_icon = isset($his_data['ext_data']['uico']) ? $his_data['ext_data']['uico'] : '';
		$his_contri_value = UserInfo::getInstance()->getUserAllCost($his_id);

		$flag = $my_contri_value > $his_contri_value ? 1 : 0;  //1=>打赢；2=>打输；3=>打平
		if (!$flag) $flag = $my_contri_value < $his_contri_value ? 2 : 3;

		$result['my_data']['id'] = $uin;
		$result['my_data']['nickname'] = $my_nickname;
		$result['my_data']['icon'] = $my_icon;
		$result['my_data']['contri_value'] = $my_contri_value;
		$result['my_data']['pk_times'] = $pk_times -1;
		$result['my_data']['my_pk_win_count'] = $my_pk_win_count;

		$result['his_data']['id'] = $his_id;
		$result['his_data']['nickname'] = $his_nickname;
		$result['his_data']['icon'] = $his_icon;
		$result['his_data']['contri_value'] = $his_contri_value;

		switch($flag){
		case 1:  //PK获胜
			$result['result'] = 1;
			$prize_id = $this->getPrizeOfWin();
			$res = getPrizeInfo($prize_id);
			$prize_name = $res[0]['prize_name'];
			$prize_icon = $res[0]['prize_icon'];
			$result['prize'] = array('prize_id'=>$prize_id,'prize_name'=>$prize_name,'prize_icon'=>$prize_icon);

			$row = registerAward($uin,$prize_id);  //登记获奖记录
			if ($row <= 0){
				$this->__db->query('rollback');
				return -1;
			}

			$time_stamp = time();  //PK胜场修改时间戳
			$sql = "update ll_activity_user_list_of_activity set pk_win_count = pk_win_count + 1,pk_win_count_update_time_stamp = {$time_stamp} where uin = {$uin}";
			$this->__db->query($sql);

			$row = $this->__db->db->affected_rows;
			if ($row <= 0){
				$this->__db->query('rollback');
				return -1;
			}

			break;

		case 2:  //PK失败
			$result['result'] = 2;
			$result['prize'] = array();
			break;

		case 3:  //PK打平
			$result['result'] = 3;
			if (!$my_contri_value) {  //贡献值为0，没有奖励
				$result['prize'] = array();
			} else {  //贡献值不为0，给安慰奖
				$prize_id = 30;  //安慰奖ID
				$res = getPrizeInfo($prize_id);
				$prize_name = $res[0]['prize_name'];
				$prize_icon = $res[0]['prize_icon'];
				$result['prize'] = array('prize_id'=>$prize_id,'prize_name'=>$prize_name,'prize_icon'=>$prize_icon);

				$row = registerAward($uin,$prize_id);  //登记获奖记录
				if ($row <= 0){
					$this->__db->query('rollback');
					return -1;
				}

			}
			break;
		}

		if (!$this->__db->query('commit')) {
			$this->__db->query('rollback');
			return -1;
		}

		return $result;
	}

	public function getUserPkRanking($top,$uin){  //获取$uin用户排名,PK$top用户榜排名
		$sql = "select uin,pk_win_count from ll_activity_user_list_of_activity order by pk_win_count desc,pk_win_count_update_time_stamp asc";
		$all_user = $this->__db->query($sql);
		$uin_user = array();  //$uin用户
		$top_user = array();  //上榜用户
		for($i=0; $i<count($all_user); $i++){
			if ($all_user[$i]['uin'] == $uin) {  //写入$uin用户数据
				$uin_user[0]['uin'] = $all_user[$i]['uin'];
				$uin_user[0]['pk_win_count'] = $all_user[$i]['pk_win_count'];
				$uin_user[0]['ranking'] = $i + 1;  //排名
				if ($uin_user[0]['pk_win_count'] == 0) {
					$uin_user[0]['ranking'] = '--';
				}
			}

			if ($i < $top && $all_user[$i]['pk_win_count'] > 0) {  //写入$top榜用户数据
				$top_user[$i]['uin'] = $all_user[$i]['uin'];
				$top_user[$i]['pk_win_count'] = $all_user[$i]['pk_win_count'];
				$top_user[$i]['ranking'] = $i + 1;  //排名
			}

			if ($i >= $top && !empty($uin_user)) {  //数据收集完毕，合并信息并退出
				$data = array_merge($uin_user,$top_user);
				return $data;
			}
		}

		$data = array_merge($uin_user,$top_user);  //当$all_user<=$top_user时，从此处退出
		return $data;
	}

	public function getPkTopUserInfo($top,$uin){  //获取$uin和$top榜用户的排名及基础信息
		$top_user_info = $this->getUserPkRanking($top,$uin);
		for($i=0; $i<count($top_user_info); $i++){
			$uin = $top_user_info[$i]['uin'];  //用户ID
			$user_data = UserInfo::getInstance()->getUserdata($uin);
			$nickname = isset($user_data['base_data']['unickname']) ? $user_data['base_data']['unickname'] : "可爱的66玩家";  //用户昵称
			$icon = isset($user_data['ext_data']['uico']) ? $user_data['ext_data']['uico'] : '';  //用户头像
			$top_user_info[$i]['nickname'] = $nickname;
			$top_user_info[$i]['icon'] = $icon;
		}
		$data = array();
		$data['uin'] = $top_user_info[0];
		$data['top'] = array_slice($top_user_info,1);
		return $data;
	}
}
