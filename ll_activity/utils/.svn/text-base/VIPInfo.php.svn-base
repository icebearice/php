<?php
/*************************************************************************
* File Name: VIPInfo.php
* Author: shaogui.li
* Created Time: Mon 18 Jun 2018 11:09:27 AM CST
* Desc: 66VIP相关 
************************************************************************/
//配置文件
require_once dirname(dirname(__FILE__)) . "/include/config.php";
require_once SYSDIR_UTILS. "/DB.php";
require_once SYSDIR_UTILS. "/REDIS.php";

class VIPInfo{
    public $db;
	public $vip_grouth;
	public $redis;

    //构造函数
    function __construct(){
        $this->db = new DB();
		$this->redis = new myRedis();
		//vip 等级成长值对应关系
		$this->vip_grouth = array(
			0 => 0,
			1 => 200,
			2 => 500,
			3 => 2000,
			4 => 4000,
			5 => 8000,
			6 => 15000,
			7 => 30000,
			8 => 100000000,
			9 => 100000000,
		);

    }

    function __destruct(){
    }


	function getVipLevel($uin){  //获取用户的vip等级，用数字表示

		$this->db->use_db('llbackend');
		$_sql = "SELECT vip_level, curr_czz FROM ll_user_vip_list WHERE uin = '{$uin}' LIMIT 1";

		$result = $this->db->query($_sql);

		if(empty($result)){
			return 0;
		}
		$vip_level = $result[0]['vip_level'] > 0 ? $result[0]['vip_level'] : 0;
		
		return $vip_level;

	}


	//获取各个VIP等级的特权
	function getVipPrivilege(){
		$n = 7;

		$all_vip = array();
		for($i = 0; $i <= $n; $i++){
			$privilege = $i > 0 ? $this->vipPrivileges($i) : array();
			$temp = array(
				'vip_level' => $i,
				'need_grouth_value' => $this->vip_grouth[$i],
				'privileges' => $privilege,
			);	

			$all_vip[] = $temp;
		}

		return $all_vip;
	}


	function vipPrivileges($vip_level){
		$key = "vip_" . $vip_level . "_privileges";

		$this->redis->use_redis('vip_privilege');
		$this->redis->redis->select(0);
		$exist = $this->redis->redis->exists($key);
		if($exist){
			$privilege = $this->redis->redis->get($key);
			$privilege = json_decode($privilege, true);
			return $privilege;
		}

		$this->db->use_db('read');

		$_sql = "SELECT title, content, url, iconUrl, shortDesc FROM ll_vip_privilege WHERE FIND_IN_SET( {$vip_level}, vip)";
		$result = $this->db->query($_sql);
		if(empty($result)){
			$privilege = array();
		}else{
			$privilege = $result;
		}

		$this->redis->redis->select(0);
		$this->redis->redis->set($key, json_encode($privilege));
		$this->redis->redis->expire($key, 3600);

		return $privilege;
	}


	//获取用户近7天成长值数据
	function getUserLast7DayData($uin){
		$this->db->use_db('read');

		$last_7_date = date('Y-m-d', time() - 86400 * 7);
		$_sql = "SELECT s_date, czz FROM ll_user_vip_statistics WHERE uin = '{$uin}'";
		$result = $this->db->query($_sql);

		$arr = array();
		if(!empty($result)){
			foreach($result as $res){
				$arr[$res['s_date']] = $res['czz'];
			}	
		}

		$charData = array();
		//获取今天的成长值
		$data = $this->getUserVipInfo($uin);
		$now_date = date('Y-m-d');
		$charData[$now_date] = $data['grouth_value'];

		$n = 7;
		for($i = 1; $i < 7; $i++){
			$date = date('Y-m-d', time() - 86400 * $i);
			if(isset($arr[$date])){
				$charData[$date] = $arr[$date];
			}else{
				continue;
				//$charData[$date] = 0;
			}
		}

		return $charData;
	}


	//获取用户当前成长值排名 超过n%
	function userMoreThanPercent($uin){
		$this->db->use_db('read');
		//获取总数
		$_sql = "SELECT COUNT(1) AS total_user FROM ll_user_vip_list";		
		$result = $this->db->query($_sql);
		$total_count = isset($result[0]['total_user']) ? $result[0]['total_user'] : 0;

		if($total_count == 0){
			return 0.99;
		}

		//获取用户成长值
		$_sql = "SELECT curr_czz FROM ll_user_vip_list WHERE uin = '{$uin}' LIMIT 1";
		$result = $this->db->query($_sql);

		$user_czz = isset($result[0]['curr_czz']) ? $result[0]['curr_czz'] : 0;
		if($user_czz == 0){
			return 0.01;
		}

		//获取比该用户成长值低的用户数
		$_sql = "SELECT COUNT(1) AS count FROM ll_user_vip_list WHERE curr_czz < '{$user_czz}'";
		$result = $this->db->query($_sql);

		//计算超过多少
		if($result[0]['count'] > 0){
			$count = $result[0]['count'];
			$percent = round($count / $total_count) == 0 ? 0.01 : round($count / $total_count, 2);
		}else{
			$percent = 0.01;			
		}

		return $percent >= 1 ? 0.99 : $percent;
		
	}


	//获取今天增加成长值
	function getTodayCzz($uin){
		if(!$uin){
			return 0;
		}

		$this->db->use_db('read');
		$today = date('Y-m-d');
		$_sql = "SELECT SUM(czz) AS czz FROM ll_user_czz_log WHERE uin = '{$uin}' AND add_time BETWEEN '{$today} 00:00:00' AND '{$today} 23:59:59' AND change_type = 1 AND status = 1";
		$result = $this->db->query($_sql);

		if(!empty($result)){
			return $result[0]['czz'] ? $result[0]['czz'] : 0;	
		}else{
			return 0;
		}
	}


	//获取所有VIP特权
	function getAllPrivilege(){
		$this->db->use_db('read');
		$_sql = "SELECT id, title, content, url, iconUrl, shortDesc, vip FROM ll_vip_privilege ORDER BY weight DESC";
		$result = $this->db->query($_sql);

		$list = array();
		if(!empty($result)){
			foreach($result as $res){
				$temp = array();
				$temp['id'] = $res['id'];
				$temp['title'] = $res['title'];
				$temp['content'] = $res['content'];
				$temp['url'] = $res['url'];
				$temp['iconUrl'] = $res['iconUrl'];
				$temp['shortDesc'] = $res['shortDesc'];
				$vip = $res['vip'];
				$temp['vips'] = $vip ? explode(",", $vip) : array();
				$list[] = $temp;
			}	
		}
		return $list;
	}
	
	//获取各个VIP等级所需成长值
	function getVipGrouth(){
		$n = 7;

		$all_vip = array();
		for($i = 0; $i <= $n; $i++){
			$temp = array(
				'vip_level' => $i,
				'need_grouth_value' => $this->vip_grouth[$i],
			);	

			$all_vip[] = $temp;
		}

		return $all_vip;
	}

	//获取用户实名认证、绑定手机状态
	function userCertificationStatus($uin){
		$resultArr = array(
			'real_name_certification' => false,
			'bind_phone' => false,
		);
		if(!$uin){
			return $resultArr;
		}

		$_sql1 = "SELECT uin FROM ll_user_czz_log WHERE uin = '{$uin}' AND remark = '实名认证'";	

		$result1 = $this->db->query($_sql1);
		if(!empty($result1)){
			$real_name_certification = true;
		}else{
			$real_name_certification = false;
		}
		
		
		$_sql2 = "SELECT uin FROM ll_user_czz_log WHERE uin = '{$uin}' AND remark = '绑定手机'";	

		$result2 = $this->db->query($_sql2);
		if(!empty($result2)){
			$bind_phone = true;
		}else{
			$bind_phone = false;
		}
		
		$resultArr = array(
			'real_name_certification' => $real_name_certification,
			'bind_phone' => $bind_phone,
		);

		return $resultArr;
	}


	//断用户当前vip等级对比上一周期等级是否升级，是则弹窗
	function isLevelUp($uin){
		if(!$uin){
			return false;
		}

		$key = "vip_level_up_" .$uin;

		$this->redis->use_redis('vip_privilege');
		$this->redis->redis->select(0);
		$exist = $this->redis->redis->exists($key);
		if($exist){
			return false;
		}

		$this->db->use_db('read');

		$yesterday = date('Y-m-d', time() - 86400);
		$_sql = "SELECT vip_level FROM ll_user_vip_statistics WHERE uin = '{$uin}' AND s_date = '{$yesterday}' LIMIT 1";
		$result = $this->db->query($_sql);

		if(empty($result)){
			return false;
		}

		$yesterday_level = $result[0]['vip_level'];

		//获取当前VIP等级	
		$data = $this->getUserVipInfo($uin);
		$curr_level = $data['vip_level'];

		if($curr_level > $yesterday_level){
			//升级 保存到redis中，下次进入判断redis中是否存在该key，存在则不弹升级弹窗
			$ttl = strtotime(date('Y-m-d 23:59:59', time())) - time();
			$this->redis->redis->select(0);
			$this->redis->redis->set($key, 1);
			$this->redis->redis->expire($key, $ttl);

			return true;
		}else{
			return false;
		}
	}


	//拉取成长值任务列表
	function getTaskList(){
		$returnArr = array(
			'pay_tab' => array(
				array(
					'task_id' => '7',
					'task_name' => '新游首充',
					'task_desc' => '成长值+15',
					'task_icon' => '',
					'max_reward' => '每日最多+75',
				),	
				array(
					'task_id' => '8',
					'task_name' => '平台币充值',
					'task_desc' => '冲得越多，送得越多',
					'task_icon' => '',
					'max_reward' => '无上限',
				),	
			),
			'active_tab' => array(
				array(
					'task_id' => '3',
					'task_name' => '登录游戏',
					'task_desc' => '成长值+1',
					'task_icon' => '',
					'max_reward' => '每日最多+1',
				),	
				array(
					'task_id' => '4',
					'task_name' => '登录APP',
					'task_desc' => '成长值+1',
					'task_icon' => '',
					'max_reward' => '每日最多+1',
				),
				array(
					'task_id' => '5',
					'task_name' => '登录新游戏',
					'task_desc' => '成长值+2',
					'task_icon' => '',
					'max_reward' => '每日最多+2',
				),
				array(
					'task_id' => '6',
					'task_name' => '参与活动',
					'task_desc' => '参与平台活动，赚成长值',
					'task_icon' => '',
					'max_reward' => '无上限',
				),
			),
			'common_tab' => array(
				array(
					'task_id' => '1',
					'task_name' => '实名认证',
					'task_desc' => '成长值+30',
					'task_icon' => '',
					'max_reward' => '固定的成长值',
				),	
				array(
					'task_id' => '2',
					'task_name' => '绑定手机',
					'task_desc' => '成长值+15',
					'task_icon' => '',
					'max_reward' => '固定的成长值',
				),	
			),
		);
		$this->db->use_db('read');

		$_sql = "SELECT id, task_name, task_desc, task_icon, task_reward, task_type, repeate_type, day_limit FROM ll_growup_task_list";
		$result = $this->db->query($_sql);

		if(empty($result)){
			return $returnArr;	
		}else{
			$returnArr = array(
				'pay_tab' => array(),
				'active_tab' => array(),
				'common_tab' => array(),
			);	

			foreach($result as $res){
				$task_id = $res['id'];
				$task_name = $res['task_name'];
				$task_desc = $res['task_desc'];
				$task_icon = $res['task_icon'];
				$task_reward = $res['task_reward'];
				$task_type = $res['task_type'];
				$repeate_type = $res['repeate_type'];
				$day_limit = $res['day_limit'];

				$arr = array(
					'task_id' => $task_id,
					'task_name' => $task_name,
					'task_desc' => $task_desc,
					'task_icon' => $task_icon,
				);
				if($task_type == 3){
					$arr['max_reward'] = $day_limit == 0 ? '无上限' : '每日最多+' . $task_reward * $day_limit;
					$returnArr['pay_tab'][] = $arr;
				}elseif($task_type == 2){
					$arr['max_reward'] = $repeate_type == 0 ? '无上限' : '每日最多+' . $task_reward;
					$returnArr['active_tab'][] = $arr;
				}else{
					$arr['max_reward'] = '固定的成长值';
					$returnArr['common_tab'][] = $arr;
				}
			}
			return $returnArr;
		}
	}
}
